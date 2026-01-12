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

namespace tool_frictionradar\friction;

defined('MOODLE_INTERNAL') || die();

/**
 * F01 – Cognitive Overload / Kognitive Überlastung
 *
 * Measures simultaneous cognitive load caused by parallel mandatory activities,
 * dense mandatory resources and textual complexity.
 */
class f01_cognitive_overload extends abstract_friction {

    public function get_key(): string {
        return 'f01';
    }

    public function calculate(int $courseid, int $windowdays): array {

        $A = $this->parallel_mandatory_activities($courseid); // ['avg','norm']
        $B = $this->mandatory_resource_density($courseid);   // 0..1
        $C = $this->textual_complexity($courseid);           // ['avg','norm']

        $score = $this->clamp(
            (int)round(
                100 * (
                    0.5 * $A['norm'] +
                    0.3 * $B +
                    0.2 * $C['norm']
                )
            )
        );

        return [
            'score' => $score,
            'breakdown' => [
                'formula' =>
                    'score = clamp( round( 100 * (0.5*A + 0.3*B + 0.2*C) ), 0, 100 )',
                'inputs' => [
                    [
                        'key'   => 'A',
                        'label' => 'Parallel mandatory activities (avg per section)',
                        'value' => round($A['avg'], 2),
                    ],
                    [
                        'key'   => 'B',
                        'label' => 'Mandatory resource density',
                        'value' => round($B, 3),
                    ],
                    [
                        'key'   => 'C',
                        'label' => 'Average textual complexity (normalized)',
                        'value' => round($C['norm'], 3),
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

        // Normalize: ≥5 mandatory activities per section is heavy
        $norm = min(1.0, max(0.0, $avg / 5.0));

        return [
            'avg'  => $avg,
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

        // Mandatory activities
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

        // Mandatory resources
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

        // Normalize: ≥2 resources per mandatory activity is heavy
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

        $rows = $DB->get_records_sql($sql, ['courseid'=>$courseid]);

        $totalchars = 0;
        $count = 0;

        foreach ($rows as $r) {
            $intro = null;

            switch ($r->name) {
                case 'assign':
                    $intro = $DB->get_field('assign', 'intro', ['id'=>$r->instance]);
                    break;
                case 'quiz':
                    $intro = $DB->get_field('quiz', 'intro', ['id'=>$r->instance]);
                    break;
                case 'forum':
                    $intro = $DB->get_field('forum', 'intro', ['id'=>$r->instance]);
                    break;
                case 'lesson':
                    $intro = $DB->get_field('lesson', 'intro', ['id'=>$r->instance]);
                    break;
                case 'page':
                    $intro = $DB->get_field('page', 'content', ['id'=>$r->instance]);
                    break;
            }

            if ($intro !== null) {
                $text = trim(strip_tags($intro));
                if ($text !== '') {
                    $totalchars += mb_strlen($text);
                    $count++;
                }
            }
        }

        if ($count === 0) {
            return ['avg' => 0.0, 'norm' => 0.0];
        }

        $avg = $totalchars / $count;

        // Normalize: ≥1500 characters average is cognitively heavy
        $norm = min(1.0, max(0.0, $avg / 1500.0));

        return [
            'avg'  => $avg,
            'norm' => $norm,
        ];
    }
}
