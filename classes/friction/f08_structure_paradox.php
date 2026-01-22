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
 * F08 – Structural Paradox / Struktur-Paradox
 *
 * Measures when strong formal structure exists but becomes counterproductive.
 */
class f08_structure_paradox extends abstract_friction {

    public function get_key(): string {
        return 'f08';
    }

    public function calculate(int $courseid, int $windowdays): array {

        $stats = $this->structure_stats($courseid);

        $A = $this->structure_overlay($stats);        // 0..1
        $B = $this->redundant_structure_signals($stats); // 0..1
        $C = $this->structure_inconsistency($stats); // 0..1

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
                    "A = structural overlay\n" .
                    "B = redundant structure signals\n" .
                    "C = structural inconsistency",
                'inputs' => [
                    ['key'=>'sections','label'=>'Non-empty sections','value'=>$stats['sections']],
                    ['key'=>'modules','label'=>'Visible modules','value'=>$stats['modules']],
                    ['key'=>'structure_modules','label'=>'Structure modules','value'=>$stats['structuremodules']],
                    ['key'=>'A','label'=>'Structural overlay (0..1)','value'=>round($A,3)],
                    ['key'=>'B','label'=>'Redundant structure signals (0..1)','value'=>round($B,3)],
                    ['key'=>'C','label'=>'Structural inconsistency (0..1)','value'=>round($C,3)],
                ],
                'notes' => $this->str('notes_f08', $windowdays),
            ],
        ];
    }

    /**
     * Gather structure-related statistics.
     */
    private function structure_stats(int $courseid): array {
        global $DB;

        $sql = "SELECT
                    cs.id AS sectionid,
                    COUNT(cm.id) AS modulecount
                FROM {course_sections} cs
                LEFT JOIN {course_modules} cm
                       ON cm.section = cs.id
                      AND cm.visible = 1
                      AND cm.deletioninprogress = 0
                WHERE cs.course = :courseid
                  AND cs.section >= 0
                GROUP BY cs.id";

        $rows = $DB->get_records_sql($sql, ['courseid'=>$courseid]);

        $sectionloads = [];
        $nonemptysections = 0;
        $modules = 0;

        foreach ($rows as $r) {
            $count = (int)$r->modulecount;
            if ($count > 0) {
                $nonemptysections++;
                $sectionloads[] = $count;
                $modules += $count;
            }
        }

        // Structure-signaling modules
        $sql = "SELECT COUNT(cm.id)
                  FROM {course_modules} cm
                  JOIN {modules} m ON m.id = cm.module
                 WHERE cm.course = :courseid
                   AND cm.visible = 1
                   AND cm.deletioninprogress = 0
                   AND m.name IN ('label','book','folder')";

        $structuremodules = (int)$DB->get_field_sql($sql, ['courseid'=>$courseid]);

        return [
            'sections' => $nonemptysections,
            'modules' => $modules,
            'structuremodules' => $structuremodules,
            'sectionloads' => $sectionloads,
        ];
    }

    /**
     * A – Structural overlay.
     *
     * Many sections combined with many modules → high formal structure.
     */
    private function structure_overlay(array $stats): float {
        if ($stats['sections'] <= 0) {
            return 0.0;
        }

        $avg = $stats['modules'] / max(1, $stats['sections']);

        // Normalize: >10 modules per section is already "heavy".
        $norm = $avg / 10.0;

        return min(1.0, max(0.0, $norm));
    }

    /**
     * B – Redundant structure signals.
     *
     * Many labels/books/folders relative to total modules.
     */
    private function redundant_structure_signals(array $stats): float {
        if ($stats['modules'] <= 0) {
            return 0.0;
        }

        $ratio = $stats['structuremodules'] / $stats['modules'];

        // >30% structure modules is excessive.
        return min(1.0, max(0.0, $ratio / 0.30));
    }

    /**
     * C – Structural inconsistency.
     *
     * High variance in section sizes reduces predictability.
     * Uses coefficient of variation.
     */
    private function structure_inconsistency(array $stats): float {
        $loads = $stats['sectionloads'];
        if (count($loads) < 2) {
            return 0.0;
        }

        $mean = array_sum($loads) / count($loads);
        if ($mean <= 0) {
            return 0.0;
        }

        $var = 0.0;
        foreach ($loads as $l) {
            $d = $l - $mean;
            $var += $d * $d;
        }
        $var /= count($loads);

        $std = sqrt($var);
        $cv = $std / $mean;

        // Normalize: cv >= 1 is very inconsistent.
        return min(1.0, max(0.0, $cv / 1.0));
    }
}
