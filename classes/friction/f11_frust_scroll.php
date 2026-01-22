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
 * F11 – Frustrated Scrolling / Frust-Scrollen
 *
 * Measures structural conditions that force learners into excessive scrolling.
 */
class f11_frust_scroll extends abstract_friction {

    public function get_key(): string {
        return 'f11';
    }

    public function calculate(int $courseid, int $windowdays): array {

        $stats = $this->scroll_stats($courseid);

        $A = $this->course_length($stats);        // 0..1
        $B = $this->section_overload($stats);     // 0..1
        $C = $this->missing_anchors($stats);      // 0..1

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
                    "A = overall course length\n" .
                    "B = section overload\n" .
                    "C = missing navigational anchors",
                'inputs' => [
                    ['key'=>'sections','label'=>'Non-empty sections','value'=>$stats['sections']],
                    ['key'=>'modules','label'=>'Visible modules','value'=>$stats['modules']],
                    ['key'=>'avg_section_size','label'=>'Average modules per section','value'=>round($stats['avgsection'],2)],
                    ['key'=>'labels','label'=>'Label modules','value'=>$stats['labels']],
                    ['key'=>'A','label'=>'Course length (0..1)','value'=>round($A,3)],
                    ['key'=>'B','label'=>'Section overload (0..1)','value'=>round($B,3)],
                    ['key'=>'C','label'=>'Missing anchors (0..1)','value'=>round($C,3)],
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

        // Sections & modules per section
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

        // Count label modules (used as anchors/headings)
        $sql = "SELECT COUNT(cm.id)
                  FROM {course_modules} cm
                  JOIN {modules} m ON m.id = cm.module
                 WHERE cm.course = :courseid
                   AND cm.visible = 1
                   AND cm.deletioninprogress = 0
                   AND m.name = 'label'";

        $labels = (int)$DB->get_field_sql($sql, ['courseid'=>$courseid]);

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

        // >12 modules per section is heavy scrolling.
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
