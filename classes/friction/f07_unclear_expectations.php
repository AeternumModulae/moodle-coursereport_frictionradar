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
 * F07 – Unclear Expectations / Unklare Erwartungen
 *
 * Measures how clearly expectations are communicated structurally.
 */
class f07_unclear_expectations extends abstract_friction
{
    /**
     * Return the friction key.
     *
     * @return string
     */
    public function get_key(): string {
        return 'f07';
    }

    /**
     * Calculate score and breakdown for a course.
     *
     * @param int $courseid Course id.
     * @param int $windowdays Window size in days.
     * @return array Calculation result.
     */
    public function calculate(int $courseid, int $windowdays): array {
        $a = $this->mandatory_without_due_dates($courseid); // Normalized 0..1.
        $b = $this->graded_without_description($courseid); // Normalized 0..1.
        $c = $this->missing_expectation_anchor($courseid); // Normalized 0..1.

        $score = $this->clamp(
            (int)round(
                100 * (0.45 * $a + 0.35 * $b + 0.20 * $c)
            )
        );

        return [
            'score' => $score,
            'breakdown' => [
                'formula' => $this->str('formula_f07'),
                'inputs' => [
                    ['key' => 'A', 'label' => $this->str('input_f07_a'), 'value' => round($a, 3)],
                    ['key' => 'B', 'label' => $this->str('input_f07_b'), 'value' => round($b, 3)],
                    ['key' => 'C', 'label' => $this->str('input_f07_c'), 'value' => $c],
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

        $cms = $DB->get_records_sql($sql, ['courseid' => $courseid]);

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
                    $duedate = $DB->get_field('assign', 'duedate', ['id' => $cm->instance]);
                    break;
                case 'quiz':
                    $duedate = $DB->get_field('quiz', 'timeclose', ['id' => $cm->instance]);
                    break;
                case 'lesson':
                    $duedate = $DB->get_field('lesson', 'deadline', ['id' => $cm->instance]);
                    break;
                case 'workshop':
                    $duedate = $DB->get_field('workshop', 'submissionend', ['id' => $cm->instance]);
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

        $section0 = $DB->get_record(
            'course_sections',
            [
                'course' => $courseid,
                'section' => 0,
            ],
            'id, summary'
        );

        $hassummary = !empty(trim(strip_tags((string)($section0->summary ?? ''))));

        $sql = "SELECT COUNT(cm.id)
                  FROM {course_modules} cm
                  JOIN {course_sections} cs ON cs.id = cm.section
                  JOIN {modules} m ON m.id = cm.module
                 WHERE cs.course = :courseid
                   AND cs.section = 0
                   AND cm.visible = 1
                   AND cm.deletioninprogress = 0
                   AND m.name IN ('page','book','resource','url','label')";

        $hasresource = ((int)$DB->get_field_sql($sql, ['courseid' => $courseid])) > 0;

        return ($hassummary || $hasresource) ? 0 : 1;
    }
}
