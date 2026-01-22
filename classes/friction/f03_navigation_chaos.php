<?php
/**
 * Copyright (c) 2026 Jan Svoboda <jan.svoboda@bittra.de>
 * Project: Aeternum Modulae – https://aeternummodulae.com
 *
 * This file is part of the Aeternum Modulae Moodle plugin "Friction Radar".
 *
 * Licensed under the GNU General Public License v3.0 or later.
 * https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace coursereport_frictionradar\friction;

defined('MOODLE_INTERNAL') || die();

/**
 * F03 – Navigation Chaos / Navigationschaos
 *
 * v1 metrics (no logstore required, robust):
 * A = structural fragmentation (many sections with small bits -> higher)
 * B = section load imbalance (irregular distribution of activities across sections -> higher)
 * C = module-type entropy (many types used in mixed distribution -> higher)
 *
 * score = clamp( round( 100 * (0.4*A + 0.35*B + 0.25*C) ), 0, 100 )
 */
class f03_navigation_chaos extends abstract_friction {

    public function get_key(): string {
        return 'f03';
    }

    public function calculate(int $courseid, int $windowdays): array {
        $stats = $this->course_structure_stats($courseid);

        $A = $this->structural_fragmentation($stats);     // 0..1
        $B = $this->section_load_imbalance($stats);       // 0..1
        $C = $this->module_type_entropy($stats);          // 0..1

        $score = $this->clamp(
            (int)round(
                100 * (0.4 * $A + 0.35 * $B + 0.25 * $C)
            )
        );

        return [
            'score' => $score,
            'breakdown' => [
                'formula' =>
                    "score = clamp( round( 100 * (0.4*A + 0.35*B + 0.25*C) ), 0, 100 )\n\n" .
                    "A = structural fragmentation\n" .
                    "B = section load imbalance\n" .
                    "C = module-type entropy",
                'inputs' => [
                    ['key' => 'sections_nonempty', 'label' => 'Non-empty sections (visible)', 'value' => (int)$stats['nonemptysections']],
                    ['key' => 'modules_total',     'label' => 'Visible modules (total)',      'value' => (int)$stats['totalmodules']],
                    ['key' => 'types_unique',      'label' => 'Unique module types',          'value' => (int)$stats['uniquetypes']],
                    ['key' => 'A', 'label' => 'Structural fragmentation (0..1)', 'value' => round($A, 3)],
                    ['key' => 'B', 'label' => 'Section load imbalance (0..1)',   'value' => round($B, 3)],
                    ['key' => 'C', 'label' => 'Module-type entropy (0..1)',      'value' => round($C, 3)],
                ],
                'notes' => $this->str('notes_f03', $windowdays),
            ],
        ];
    }

    /**
     * Pull structural statistics from Moodle core tables (no logstore needed).
     *
     * We consider:
     * - visible, not-deleted course_modules
     * - sections that contain at least one such module (non-empty)
     * - module type distribution
     */
    private function course_structure_stats(int $courseid): array {
        global $DB;

        $sql = "SELECT
                    cs.id AS sectionid,
                    cs.section AS sectionnum,
                    m.name AS modname,
                    COUNT(cm.id) AS modcount
                FROM {course_sections} cs
                LEFT JOIN {course_modules} cm
                       ON cm.section = cs.id
                      AND cm.visible = 1
                      AND cm.deletioninprogress = 0
                LEFT JOIN {modules} m
                       ON m.id = cm.module
                WHERE cs.course = :courseid
                  AND cs.section >= 0
                GROUP BY cs.id, cs.section, m.name
                ORDER BY cs.section ASC";

        $rows = $DB->get_records_sql($sql, ['courseid' => $courseid]);

        // Build per-section module counts and type distribution.
        $persection = [];   // sectionid => module count
        $types = [];        // modname => count
        $totalmodules = 0;

        foreach ($rows as $r) {
            $sid = (int)$r->sectionid;
            $count = (int)$r->modcount;

            // Rows with NULL modname happen for empty sections (LEFT JOIN).
            if (!isset($persection[$sid])) {
                $persection[$sid] = 0;
            }

            if (!empty($r->modname) && $count > 0) {
                $persection[$sid] += $count;
                $totalmodules += $count;

                $mod = (string)$r->modname;
                if (!isset($types[$mod])) {
                    $types[$mod] = 0;
                }
                $types[$mod] += $count;
            }
        }

        $nonemptysections = 0;
        foreach ($persection as $c) {
            if ($c > 0) {
                $nonemptysections++;
            }
        }

        $uniquetypes = count($types);

        // Convert persection to numeric list for stats.
        $sectionloads = array_values(array_filter($persection, fn($v) => $v > 0));

        return [
            'nonemptysections' => $nonemptysections,
            'totalmodules'     => $totalmodules,
            'uniquetypes'      => $uniquetypes,
            'sectionloads'     => $sectionloads, // list of counts per non-empty section
            'types'            => $types,        // map
        ];
    }

    /**
     * A – Structural fragmentation (0..1).
     *
     * Many non-empty sections with low average load feels like "scroll & hunt".
     * We approximate this by:
     *   A = clamp( nonemptysections / 12, 0..1 )
     * 12 is a typical "weekly" course structure.
     */
    private function structural_fragmentation(array $stats): float {
        $s = (int)$stats['nonemptysections'];
        if ($s <= 0) {
            return 0.0;
        }
        return min(1.0, max(0.0, $s / 12.0));
    }

    /**
     * B – Section load imbalance (0..1).
     *
     * Irregular distribution of activities across sections increases disorientation.
     * We use coefficient of variation (std/mean) on module counts per section:
     *   cv = std / mean
     * Normalize with cv/1.0 (cv >= 1 means very uneven).
     */
    private function section_load_imbalance(array $stats): float {
        $loads = $stats['sectionloads'] ?? [];
        if (empty($loads)) {
            return 0.0;
        }

        $n = count($loads);
        $mean = array_sum($loads) / $n;
        if ($mean <= 0.000001) {
            return 0.0;
        }

        $var = 0.0;
        foreach ($loads as $x) {
            $dx = $x - $mean;
            $var += $dx * $dx;
        }
        $var /= $n;
        $std = sqrt($var);

        $cv = $std / $mean; // 0.. potentially >1
        $norm = $cv / 1.0;

        return min(1.0, max(0.0, $norm));
    }

    /**
     * C – Module-type entropy (0..1).
     *
     * More mixed module type distribution tends to feel less predictable.
     * We compute Shannon entropy normalized by log(K):
     *   H = -sum(p_i * ln(p_i)) / ln(K)
     * where K is number of unique module types (>=2).
     */
    private function module_type_entropy(array $stats): float {
        $types = $stats['types'] ?? [];
        $total = (int)($stats['totalmodules'] ?? 0);
        $k = (int)($stats['uniquetypes'] ?? 0);

        if ($total <= 0 || $k <= 1) {
            return 0.0;
        }

        $h = 0.0;
        foreach ($types as $count) {
            $p = $count / $total;
            if ($p > 0) {
                $h -= $p * log($p);
            }
        }

        $hmax = log($k);
        if ($hmax <= 0.0) {
            return 0.0;
        }

        $norm = $h / $hmax;
        return min(1.0, max(0.0, $norm));
    }
}
