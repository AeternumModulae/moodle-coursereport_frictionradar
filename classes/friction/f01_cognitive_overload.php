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
 * F01 – Cognitive Overload / Kognitive Überlastung
 *
 * Measures simultaneous cognitive load caused by parallel mandatory activities,
 * dense mandatory resources and textual complexity.
 */
class f01_cognitive_overload extends abstract_friction
{
    /**
     * Return the friction key.
     *
     * @return string
     */
    public function get_key(): string {
        return 'f01';
    }

    /**
     * Calculate score and breakdown for a course.
     *
     * @param int $courseid Course id.
     * @param int $windowdays Window size in days.
     * @return array Calculation result.
     */
    public function calculate(int $courseid, int $windowdays): array {
        $a = $this->parallel_mandatory_activities($courseid); // Returns avg and norm.
        $b = $this->mandatory_resource_density($courseid); // Normalized 0..1.
        $c = $this->textual_complexity($courseid); // Returns avg and norm.

        $score = $this->clamp(
            (int)round(
                100 * (
                    0.5 * $a['norm'] + 0.3 * $b + 0.2 * $c['norm']
                )
            )
        );

        return [
            'score' => $score,
            'breakdown' => [
                'formula' => $this->str('formula_f01'),
                'inputs' => [
                    [
                        'key' => 'A',
                        'label' => $this->str('input_f01_a'),
                        'value' => round($a['avg'], 2),
                    ],
                    [
                        'key' => 'B',
                        'label' => $this->str('input_f01_b'),
                        'value' => round($b, 3),
                    ],
                    [
                        'key' => 'C',
                        'label' => $this->str('input_f01_c'),
                        'value' => round($c['norm'], 3),
                    ],
                ],
                'notes' => $this->str('notes_f01', $windowdays),
            ],
        ];
    }

    /**
     * A – Parallel mandatory activities per section.
     *
     * Mandatory = completion enabled.
     */
    private function parallel_mandatory_activities(int $courseid): array {
        global $DB;

        $sql = "SELECT cs.id, COUNT(cm.id) AS cnt
                  FROM {course_sections} cs
                  JOIN {course_modules} cm ON cm.section = cs.id
                 WHERE cs.course = :courseid
                   AND cm.visible = 1
                   AND cm.deletioninprogress = 0
                   AND cm.completion <> 0
              GROUP BY cs.id";

        $rows = $DB->get_records_sql($sql, ['courseid' => $courseid]);

        if (!$rows) {
            return ['avg' => 0.0, 'norm' => 0.0];
        }

        $total = 0;
        foreach ($rows as $r) {
            $total += (int)$r->cnt;
        }

        $avg = $total / count($rows);

        // Normalize: >=5 mandatory activities per section is heavy.
        $norm = min(1.0, max(0.0, $avg / 5.0));

        return [
            'avg' => $avg,
            'norm' => $norm,
        ];
    }

    /**
     * B – Mandatory resource density.
     *
     * Mandatory resources per mandatory activity.
     */
    private function mandatory_resource_density(int $courseid): float {
        global $DB;

        // Mandatory activities.
        $mandatory = (int)$DB->get_field_sql(
            "SELECT COUNT(id)
               FROM {course_modules}
              WHERE course = :courseid
                AND visible = 1
                AND deletioninprogress = 0
                AND completion <> 0",
            ['courseid' => $courseid]
        );

        if ($mandatory === 0) {
            return 0.0;
        }

        // Mandatory resources.
        $resources = (int)$DB->get_field_sql(
            "SELECT COUNT(cm.id)
               FROM {course_modules} cm
               JOIN {modules} m ON m.id = cm.module
              WHERE cm.course = :courseid
                AND cm.visible = 1
                AND cm.deletioninprogress = 0
                AND cm.completion <> 0
                AND m.name IN ('resource','page','book','folder','url')",
            ['courseid' => $courseid]
        );

        $density = $resources / $mandatory;

        // Normalize: >=2 resources per mandatory activity is heavy.
        return min(1.0, max(0.0, $density / 2.0));
    }

    /**
     * C – Textual complexity (very conservative proxy).
     *
     * Uses intro text length as complexity signal.
     */
    private function textual_complexity(int $courseid): array {
        global $DB;

        $sql = "SELECT m.name, cm.instance
                  FROM {course_modules} cm
                  JOIN {modules} m ON m.id = cm.module
                 WHERE cm.course = :courseid
                   AND cm.visible = 1
                   AND cm.deletioninprogress = 0";

        $rows = $DB->get_records_sql($sql, ['courseid' => $courseid]);

        $totalchars = 0;
        $count = 0;

        foreach ($rows as $r) {
            $intro = null;

            switch ($r->name) {
                case 'assign':
                    if ($this->table_exists('assign')) {
                        $intro = $DB->get_field('assign', 'intro', ['id' => $r->instance]);
                    }
                    break;
                case 'quiz':
                    if ($this->table_exists('quiz')) {
                        $intro = $DB->get_field('quiz', 'intro', ['id' => $r->instance]);
                    }
                    break;
                case 'forum':
                    if ($this->table_exists('forum')) {
                        $intro = $DB->get_field('forum', 'intro', ['id' => $r->instance]);
                    }
                    break;
                case 'lesson':
                    if ($this->table_exists('lesson')) {
                        $intro = $DB->get_field('lesson', 'intro', ['id' => $r->instance]);
                    }
                    break;
                case 'page':
                    if ($this->table_exists('page')) {
                        $intro = $DB->get_field('page', 'content', ['id' => $r->instance]);
                    }
                    break;
            }

            if ($intro !== null) {
                $text = trim(strip_tags($intro));
                if ($text !== '') {
                    $totalchars += \core_text::strlen($text);
                    $count++;
                }
            }
        }

        if ($count === 0) {
            return ['avg' => 0.0, 'norm' => 0.0];
        }

        $avg = $totalchars / $count;

        // Normalize: >=1500 characters average is cognitively heavy.
        $norm = min(1.0, max(0.0, $avg / 1500.0));

        return [
            'avg' => $avg,
            'norm' => $norm,
        ];
    }
}
