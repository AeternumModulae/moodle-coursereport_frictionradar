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
 * F04 – Overambitious Entry / Überambitionierter Einstieg
 *
 * Measures how demanding the initial phase of a course is.
 */
class f04_overambitious_entry extends abstract_friction
{
    /**
     * Return the friction key.
     *
     * @return string
     */
    public function get_key(): string {
        return 'f04';
    }

    /**
     * Calculate score and breakdown for a course.
     *
     * @param int $courseid Course id.
     * @param int $windowdays Window size in days.
     * @return array Calculation result.
     */
    public function calculate(int $courseid, int $windowdays): array {
        // Number of sections considered "entry phase".
        $entrysections = 2;

        $a = $this->mandatory_activities_in_entry($courseid, $entrysections); // Normalized 0..1.
        $b = $this->early_workload_proxy($courseid, $entrysections); // Normalized 0..1.
        $c = $this->entry_content_complexity($courseid, $entrysections); // Normalized 0..1.

        $score = $this->clamp(
            (int)round(
                100 * (0.45 * $a + 0.35 * $b + 0.20 * $c)
            )
        );

        return [
            'score' => $score,
            'breakdown' => [
                'formula' => $this->str('formula_f04'),
                'inputs' => [
                    ['key' => 'A', 'label' => $this->str('input_f04_a'), 'value' => round($a, 3)],
                    ['key' => 'B', 'label' => $this->str('input_f04_b'), 'value' => round($b, 3)],
                    ['key' => 'C', 'label' => $this->str('input_f04_c'), 'value' => round($c, 3)],
                    ['key' => 'sections', 'label' => $this->str('input_f04_sections'), 'value' => $entrysections],
                ],
                'notes' => $this->str('notes_f04', $windowdays),
            ],
        ];
    }

    /**
     * A – Mandatory activities in first N sections.
     * Normalized using /8 (very demanding start).
     */
    private function mandatory_activities_in_entry(int $courseid, int $sections): float {
        global $DB;

        $sql = "SELECT COUNT(cm.id)
                  FROM {course_modules} cm
                  JOIN {course_sections} cs ON cs.id = cm.section
                 WHERE cs.course = :courseid
                   AND cs.section BETWEEN 0 AND :maxsection
                   AND cm.visible = 1
                   AND cm.deletioninprogress = 0
                   AND cm.completion <> 0";

        $count = (int)$DB->get_field_sql($sql, [
            'courseid' => $courseid,
            'maxsection' => $sections,
        ]);

        return min(1.0, max(0.0, $count / 8.0));
    }

    /**
     * B – Early workload proxy.
     * Counts "demanding" activity types in entry sections.
     */
    private function early_workload_proxy(int $courseid, int $sections): float {
        global $DB;

        $demanding = [
            'assign', 'quiz', 'workshop', 'lesson', 'scorm', 'lti', 'data', 'feedback',
        ];

        [$insql, $params] = $DB->get_in_or_equal($demanding, SQL_PARAMS_NAMED);

        $sql = "SELECT COUNT(cm.id)
                  FROM {course_modules} cm
                  JOIN {modules} m ON m.id = cm.module
                  JOIN {course_sections} cs ON cs.id = cm.section
                 WHERE cs.course = :courseid
                   AND cs.section BETWEEN 0 AND :maxsection
                   AND cm.visible = 1
                   AND cm.deletioninprogress = 0
                   AND m.name $insql";

        $params['courseid'] = $courseid;
        $params['maxsection'] = $sections;

        $count = (int)$DB->get_field_sql($sql, $params);

        // Normalize: 6 demanding items early is already heavy.
        return min(1.0, max(0.0, $count / 6.0));
    }

    /**
     * C – Entry content complexity (text + structure).
     */
    private function entry_content_complexity(int $courseid, int $sections): float {
        global $DB;

        $sectionsdata = $DB->get_records_select(
            'course_sections',
            'course = :courseid AND section BETWEEN 0 AND :maxsection',
            ['courseid' => $courseid, 'maxsection' => $sections],
            '',
            'id, summary'
        );

        $text = '';
        foreach ($sectionsdata as $s) {
            $text .= ' ' . strip_tags((string)$s->summary);
        }

        $words = preg_split('/\s+/u', trim($text), -1, PREG_SPLIT_NO_EMPTY);
        $wordcount = is_array($words) ? count($words) : 0;

        if ($wordcount === 0) {
            return 0.0;
        }

        $longwords = 0;
        foreach ($words as $w) {
            if (\core_text::strlen($w) >= 12) {
                $longwords++;
            }
        }

        $ratio = $longwords / $wordcount;

        // Normalize: 15% long words is already demanding.
        return min(1.0, max(0.0, $ratio / 0.15));
    }
}
