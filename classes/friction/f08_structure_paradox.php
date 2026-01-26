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
 * F08 – Structural Paradox / Struktur-Paradox
 *
 * Measures when strong formal structure exists but becomes counterproductive.
 */
class f08_structure_paradox extends abstract_friction
{
    /**
     * Return the friction key.
     *
     * @return string
     */
    public function get_key(): string {
        return 'f08';
    }

    /**
     * Calculate score and breakdown for a course.
     *
     * @param int $courseid Course id.
     * @param int $windowdays Window size in days.
     * @return array Calculation result.
     */
    public function calculate(int $courseid, int $windowdays): array {
        $stats = $this->structure_stats($courseid);

        $a = $this->structure_overlay($stats); // Normalized 0..1.
        $b = $this->redundant_structure_signals($stats); // Normalized 0..1.
        $c = $this->structure_inconsistency($stats); // Normalized 0..1.

        $score = $this->clamp(
            (int)round(
                100 * (0.4 * $a + 0.35 * $b + 0.25 * $c)
            )
        );

        return [
            'score' => $score,
            'breakdown' => [
                'formula' => $this->str('formula_f08'),
                'inputs' => [
                    ['key' => 'sections', 'label' => $this->str('input_f08_sections'), 'value' => $stats['sections']],
                    ['key' => 'modules', 'label' => $this->str('input_f08_modules'), 'value' => $stats['modules']],
                    [
                        'key' => 'structure_modules',
                        'label' => $this->str('input_f08_structure_modules'),
                        'value' => $stats['structuremodules'],
                    ],
                    ['key' => 'A', 'label' => $this->str('input_f08_a'), 'value' => round($a, 3)],
                    ['key' => 'B', 'label' => $this->str('input_f08_b'), 'value' => round($b, 3)],
                    ['key' => 'C', 'label' => $this->str('input_f08_c'), 'value' => round($c, 3)],
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

        $rows = $DB->get_records_sql($sql, ['courseid' => $courseid]);

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

        // Structure-signaling modules.
        $sql = "SELECT COUNT(cm.id)
                  FROM {course_modules} cm
                  JOIN {modules} m ON m.id = cm.module
                 WHERE cm.course = :courseid
                   AND cm.visible = 1
                   AND cm.deletioninprogress = 0
                   AND m.name IN ('label','book','folder')";

        $structuremodules = (int)$DB->get_field_sql($sql, ['courseid' => $courseid]);

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

        // More than 30% structure modules is excessive.
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

        // Normalize: CV >= 1 is very inconsistent.
        return min(1.0, max(0.0, $cv / 1.0));
    }
}
