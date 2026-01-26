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
 * F09 – Resource Overload / Ressourcenüberversorgung
 *
 * Measures whether learners are flooded with resources relative to structure and activities.
 */
class f09_resource_overload extends abstract_friction
{
    /**
     * Return the friction key.
     *
     * @return string
     */
    public function get_key(): string {
        return 'f09';
    }

    /**
     * Calculate score and breakdown for a course.
     *
     * @param int $courseid Course id.
     * @param int $windowdays Window size in days.
     * @return array Calculation result.
     */
    public function calculate(int $courseid, int $windowdays): array {
        $stats = $this->resource_stats($courseid);

        $a = $this->resource_density($stats); // Normalized 0..1.
        $b = $this->resource_share($stats); // Normalized 0..1.
        $c = $this->resource_redundancy($stats); // Normalized 0..1.

        $score = $this->clamp(
            (int)round(
                100 * (0.45 * $a + 0.35 * $b + 0.20 * $c)
            )
        );

        return [
            'score' => $score,
            'breakdown' => [
                'formula' => $this->str('formula_f09'),
                'inputs' => [
                    ['key' => 'sections', 'label' => $this->str('input_f09_sections'), 'value' => $stats['sections']],
                    [
                        'key' => 'modules_total',
                        'label' => $this->str('input_f09_modules_total'),
                        'value' => $stats['modules'],
                    ],
                    [
                        'key' => 'resources_total',
                        'label' => $this->str('input_f09_resources_total'),
                        'value' => $stats['resources'],
                    ],
                    [
                        'key' => 'resources_file',
                        'label' => $this->str('input_f09_resources_file'),
                        'value' => $stats['resourcefiles'],
                    ],
                    ['key' => 'A', 'label' => $this->str('input_f09_a'), 'value' => round($a, 3)],
                    ['key' => 'B', 'label' => $this->str('input_f09_b'), 'value' => round($b, 3)],
                    ['key' => 'C', 'label' => $this->str('input_f09_c'), 'value' => round($c, 3)],
                ],
                'notes' => $this->str('notes_f09', $windowdays),
            ],
        ];
    }

    /**
     * Collect basic stats for resource overload.
     */
    private function resource_stats(int $courseid): array {
        global $DB;

        // Non-empty sections & total visible modules.
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

        foreach ($rows as $r) {
            $count = (int)$r->modulecount;
            if ($count > 0) {
                $sections++;
                $modules += $count;
            }
        }

        // Count resource-type modules.
        // Define "resources" broadly: resource (file), page, url, book, folder, label.
        $sql = "SELECT
                    SUM(
                        CASE WHEN m.name IN ('resource','page','url','book','folder','label')
                             THEN 1 ELSE 0 END
                    ) AS resources,
                    SUM(CASE WHEN m.name = 'resource' THEN 1 ELSE 0 END) AS resourcefiles
                FROM {course_modules} cm
                JOIN {modules} m ON m.id = cm.module
               WHERE cm.course = :courseid
                 AND cm.visible = 1
                 AND cm.deletioninprogress = 0";

        $r = $DB->get_record_sql($sql, ['courseid' => $courseid]);

        $resources = (int)($r->resources ?? 0);
        $resourcefiles = (int)($r->resourcefiles ?? 0);

        return [
            'sections' => $sections,
            'modules' => $modules,
            'resources' => $resources,
            'resourcefiles' => $resourcefiles,
        ];
    }

    /**
     * A – Resource density (resources per non-empty section).
     * Normalize: 6 resources/section is "heavy".
     */
    private function resource_density(array $stats): float {
        $sections = max(1, (int)$stats['sections']);
        $resources = (int)$stats['resources'];

        $density = $resources / $sections;
        return min(1.0, max(0.0, $density / 6.0));
    }

    /**
     * B – Resource share (resources / all modules).
     * Normalize: >70% resources means the course is mostly materials, little activity.
     */
    private function resource_share(array $stats): float {
        $modules = (int)$stats['modules'];
        if ($modules <= 0) {
            return 0.0;
        }

        $share = $stats['resources'] / $modules;
        return min(1.0, max(0.0, $share / 0.70));
    }

    /**
     * C – Redundancy proxy (many file resources among resources).
     * Normalize: if >60% of resources are plain file resources, redundancy risk increases.
     */
    private function resource_redundancy(array $stats): float {
        $resources = (int)$stats['resources'];
        if ($resources <= 0) {
            return 0.0;
        }

        $ratio = $stats['resourcefiles'] / $resources;
        return min(1.0, max(0.0, $ratio / 0.60));
    }
}
