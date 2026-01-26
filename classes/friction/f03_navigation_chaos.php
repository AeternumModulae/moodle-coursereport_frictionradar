<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * Friction Radar report.
 *
 * @package    coursereport_frictionradar
 * @copyright  2026 Jan Svoboda <jan.svoboda@bittra.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace coursereport_frictionradar\friction;


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
class f03_navigation_chaos extends abstract_friction
{
    /**
     * Return the friction key.
     *
     * @return string
     */
    public function get_key(): string {
        return 'f03';
    }

    /**
     * Calculate score and breakdown for a course.
     *
     * @param int $courseid Course id.
     * @param int $windowdays Window size in days.
     * @return array Calculation result.
     */
    public function calculate(int $courseid, int $windowdays): array {
        $stats = $this->course_structure_stats($courseid);

        $a = $this->structural_fragmentation($stats); // Normalized 0..1.
        $b = $this->section_load_imbalance($stats); // Normalized 0..1.
        $c = $this->module_type_entropy($stats); // Normalized 0..1.

        $score = $this->clamp(
            (int)round(
                100 * (0.4 * $a + 0.35 * $b + 0.25 * $c)
            )
        );

        return [
            'score' => $score,
            'breakdown' => [
                'formula' => $this->str('formula_f03'),
                'inputs' => [
                    [
                        'key' => 'sections_nonempty',
                        'label' => $this->str('input_f03_sections_nonempty'),
                        'value' => (int)$stats['nonemptysections'],
                    ],
                    [
                        'key' => 'modules_total',
                        'label' => $this->str('input_f03_modules_total'),
                        'value' => (int)$stats['totalmodules'],
                    ],
                    [
                        'key' => 'types_unique',
                        'label' => $this->str('input_f03_types_unique'),
                        'value' => (int)$stats['uniquetypes'],
                    ],
                    ['key' => 'A', 'label' => $this->str('input_f03_a'), 'value' => round($a, 3)],
                    ['key' => 'B', 'label' => $this->str('input_f03_b'), 'value' => round($b, 3)],
                    ['key' => 'C', 'label' => $this->str('input_f03_c'), 'value' => round($c, 3)],
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
        $persection = []; // Section ID to module count.
        $types = []; // Module name to count.
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
            'totalmodules' => $totalmodules,
            'uniquetypes' => $uniquetypes,
            'sectionloads' => $sectionloads, // List of counts per non-empty section.
            'types' => $types, // Map of module name to count.
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

        $cv = $std / $mean; // Normalized 0.. potentially >1.
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
