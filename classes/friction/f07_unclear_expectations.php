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
 * F07 – Unclear Expectations / Unklare Erwartungen
 *
 * Measures how clearly expectations are communicated structurally.
 */
class f07_unclear_expectations extends abstract_friction {

    public function get_key(): string {
        return 'f07';
    }

    public function calculate(int $courseid, int $windowdays): array {

        $A = $this->mandatory_without_due_dates($courseid); // 0..1
        $B = $this->graded_without_description($courseid);  // 0..1
        $C = $this->missing_expectation_anchor($courseid);  // 0..1

        $score = $this->clamp(
            (int)round(
                100 * (0.45 * $A + 0.35 * $B + 0.20 * $C)
            )
        );

        return [
            'score' => $score,
            'breakdown' => [
                'formula' =>
                    "score = clamp( round( 100 * (0.45*A + 0.35*B + 0.20*C) ), 0, 100 )\n\n" .
                    "A = mandatory activities without due dates\n" .
                    "B = graded activities without description\n" .
                    "C = missing central expectation anchor",
                'inputs' => [
                    ['key'=>'A','label'=>'Mandatory activities without due date (ratio)','value'=>round($A,3)],
                    ['key'=>'B','label'=>'Graded activities without description (ratio)','value'=>round($B,3)],
                    ['key'=>'C','label'=>'Missing expectation anchor (0 or 1)','value'=>$C],
                ],
                'notes' => $this->str('notes_f07', $windowdays),
            ],
        ];
    }

    /**
     * A – Mandatory activities without due dates.
     *
     * Mandatory = completion enabled.
     * Due date detection:
     * - looks for common date fields in module tables via course_modules + instance tables.
     * - v1 heuristic: if no due date field exists or is empty => unclear.
     */
    private function mandatory_without_due_dates(int $courseid): float {
        global $DB;

        $sql = "SELECT cm.id, cm.module, cm.instance, m.name AS modname
                  FROM {course_modules} cm
                  JOIN {modules} m ON m.id = cm.module
                 WHERE cm.course = :courseid
                   AND cm.visible = 1
                   AND cm.deletioninprogress = 0
                   AND cm.completion <> 0";

        $cms = $DB->get_records_sql($sql, ['courseid'=>$courseid]);

        if (!$cms) {
            return 0.0;
        }

        $mandatory = 0;
        $nodue = 0;

        foreach ($cms as $cm) {
            $mandatory++;

            // Known due-date capable modules (v1 heuristic).
            $duedate = null;

            switch ($cm->modname) {
                case 'assign':
                    $duedate = $DB->get_field('assign', 'duedate', ['id'=>$cm->instance]);
                    break;
                case 'quiz':
                    $duedate = $DB->get_field('quiz', 'timeclose', ['id'=>$cm->instance]);
                    break;
                case 'lesson':
                    $duedate = $DB->get_field('lesson', 'deadline', ['id'=>$cm->instance]);
                    break;
                case 'workshop':
                    $duedate = $DB->get_field('workshop', 'submissionend', ['id'=>$cm->instance]);
                    break;
                default:
                    // Other mandatory modules typically have no due date concept.
                    $duedate = null;
            }

            if (empty($duedate)) {
                $nodue++;
            }
        }

        if ($mandatory === 0) {
            return 0.0;
        }

        return min(1.0, max(0.0, $nodue / $mandatory));
    }

    /**
     * B – Graded activities without description/intro.
     */
    private function graded_without_description(int $courseid): float {
        global $DB;

        $sql = "SELECT
                    cm.id,
                    cm.instance,
                    m.name AS modname
                FROM {course_modules} cm
                JOIN {modules} m ON m.id = cm.module
                JOIN {grade_items} gi
                  ON gi.itemtype = 'mod'
                 AND gi.itemmodule = m.name
                 AND gi.iteminstance = cm.instance
                 AND gi.gradetype <> 0
               WHERE cm.course = :courseid
                 AND cm.visible = 1
                 AND cm.deletioninprogress = 0";

        $rows = $DB->get_records_sql($sql, ['courseid' => $courseid]);

        if (!$rows) {
            return 0.0;
        }

        $graded = 0;
        $nodesc = 0;

        foreach ($rows as $r) {
            $graded++;

            $intro = null;

            switch ($r->modname) {
                case 'assign':
                    $intro = $DB->get_field('assign', 'intro', ['id' => $r->instance]);
                    break;
                case 'quiz':
                    $intro = $DB->get_field('quiz', 'intro', ['id' => $r->instance]);
                    break;
                case 'forum':
                    $intro = $DB->get_field('forum', 'intro', ['id' => $r->instance]);
                    break;
                case 'lesson':
                    $intro = $DB->get_field('lesson', 'intro', ['id' => $r->instance]);
                    break;
            }

            if (trim(strip_tags((string)$intro)) === '') {
                $nodesc++;
            }
        }

        return min(1.0, max(0.0, $nodesc / $graded));
    }


    /**
     * C – Missing central expectation anchor.
     *
     * Checks for:
     * - section 0 summary
     * - OR presence of a page/book/resource in section 0
     *
     * If missing => 1, else 0.
     */
    private function missing_expectation_anchor(int $courseid): int {
        global $DB;

        $section0 = $DB->get_record('course_sections', [
            'course'=>$courseid,
            'section'=>0
        ], 'id, summary');

        $hasSummary = !empty(trim(strip_tags((string)($section0->summary ?? ''))));

        $sql = "SELECT COUNT(cm.id)
                  FROM {course_modules} cm
                  JOIN {course_sections} cs ON cs.id = cm.section
                  JOIN {modules} m ON m.id = cm.module
                 WHERE cs.course = :courseid
                   AND cs.section = 0
                   AND cm.visible = 1
                   AND cm.deletioninprogress = 0
                   AND m.name IN ('page','book','resource','url','label')";

        $hasResource = ((int)$DB->get_field_sql($sql, ['courseid'=>$courseid])) > 0;

        return ($hasSummary || $hasResource) ? 0 : 1;
    }
}
