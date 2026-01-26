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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Friction Radar report.
 *
 * @package    coursereport_frictionradar
 * @copyright  2026 Jan Svoboda <jan.svoboda@bittra.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace coursereport_frictionradar\friction;


/**
 * F11 – Frustrated Scrolling / Frust-Scrollen
 *
 * Measures structural conditions that force learners into excessive scrolling.
 */
class f11_frust_scroll extends abstract_friction
{
    /**
     * Return the friction key.
     *
     * @return string
     */
    public function get_key(): string {
        return 'f11';
    }

    /**
     * Calculate score and breakdown for a course.
     *
     * @param int $courseid Course id.
     * @param int $windowdays Window size in days.
     * @return array Calculation result.
     */
    public function calculate(int $courseid, int $windowdays): array {
        $stats = $this->scroll_stats($courseid);

        $a = $this->course_length($stats); // Normalized 0..1.
        $b = $this->section_overload($stats); // Normalized 0..1.
        $c = $this->missing_anchors($stats); // Normalized 0..1.

        $score = $this->clamp(
            (int)round(
                100 * (0.4 * $a + 0.35 * $b + 0.25 * $c)
            )
        );

        return [
            'score' => $score,
            'breakdown' => [
                'formula' => $this->str('formula_f11'),
                'inputs' => [
                    ['key' => 'sections', 'label' => $this->str('input_f11_sections'), 'value' => $stats['sections']],
                    ['key' => 'modules', 'label' => $this->str('input_f11_modules'), 'value' => $stats['modules']],
                    [
                        'key' => 'avg_section_size',
                        'label' => $this->str('input_f11_avg_section_size'),
                        'value' => round($stats['avgsection'], 2),
                    ],
                    ['key' => 'labels', 'label' => $this->str('input_f11_labels'), 'value' => $stats['labels']],
                    ['key' => 'A', 'label' => $this->str('input_f11_a'), 'value' => round($a, 3)],
                    ['key' => 'B', 'label' => $this->str('input_f11_b'), 'value' => round($b, 3)],
                    ['key' => 'C', 'label' => $this->str('input_f11_c'), 'value' => round($c, 3)],
                ],
                'notes' => $this->str('notes_f11', $windowdays),
            ],
        ];
    }

    /**
     * Collect structural stats relevant for scrolling.
     */
    private function scroll_stats(int $courseid): array {
        global $DB;

        // Sections and modules per section.
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

        $sections = 0;
        $modules = 0;
        $loads = [];

        foreach ($rows as $r) {
            $count = (int)$r->modulecount;
            if ($count > 0) {
                $sections++;
                $modules += $count;
                $loads[] = $count;
            }
        }

        $avgsection = ($sections > 0) ? ($modules / $sections) : 0;

        // Count label modules (used as anchors/headings).
        $sql = "SELECT COUNT(cm.id)
                  FROM {course_modules} cm
                  JOIN {modules} m ON m.id = cm.module
                 WHERE cm.course = :courseid
                   AND cm.visible = 1
                   AND cm.deletioninprogress = 0
                   AND m.name = 'label'";

        $labels = (int)$DB->get_field_sql($sql, ['courseid' => $courseid]);

        return [
            'sections' => $sections,
            'modules' => $modules,
            'avgsection' => $avgsection,
            'loads' => $loads,
            'labels' => $labels,
        ];
    }

    /**
     * A – Course length.
     *
     * Normalize using combined sections+modules.
     */
    private function course_length(array $stats): float {
        $value = $stats['sections'] + ($stats['modules'] / 10);
        return min(1.0, max(0.0, $value / 20.0));
    }

    /**
     * B – Section overload.
     *
     * Penalizes very large sections.
     */
    private function section_overload(array $stats): float {
        $avg = $stats['avgsection'];
        if ($avg <= 0) {
            return 0.0;
        }

        // More than 12 modules per section is heavy scrolling.
        return min(1.0, max(0.0, $avg / 12.0));
    }

    /**
     * C – Missing navigational anchors.
     *
     * Few labels relative to sections.
     */
    private function missing_anchors(array $stats): float {
        $sections = max(1, $stats['sections']);
        $labels = $stats['labels'];

        $ratio = $labels / $sections;

        // Ideally at least one label per section.
        return min(1.0, max(0.0, 1.0 - $ratio));
    }
}
