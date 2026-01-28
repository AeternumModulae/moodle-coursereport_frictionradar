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
 * F12 – Deadline Panic / Deadline-Panik
 *
 * Measures temporal overload caused by clustered or short-term deadlines.
 */
class f12_deadline_panic extends abstract_friction
{
    /**
     * Return the friction key.
     *
     * @return string
     */
    public function get_key(): string {
        return 'f12';
    }

    /**
     * Calculate score and breakdown for a course.
     *
     * @param int $courseid Course id.
     * @param int $windowdays Window size in days.
     * @return array Calculation result.
     */
    public function calculate(int $courseid, int $windowdays): array {
        $deadlines = $this->collect_deadlines($courseid, $windowdays);

        if (empty($deadlines)) {
            return $this->empty_result($windowdays);
        }

        $a = $this->deadline_density($deadlines, $windowdays); // Normalized 0..1.
        $b = $this->deadline_clustering($deadlines); // Normalized 0..1.
        $c = $this->short_notice_deadlines($deadlines); // Normalized 0..1.

        $score = $this->clamp(
            (int)round(
                100 * (0.4 * $a + 0.35 * $b + 0.25 * $c)
            )
        );

        return [
            'score' => $score,
            'breakdown' => [
                'formula' => $this->str('formula_f12'),
                'inputs' => [
                    [
                        'key' => 'deadlines',
                        'label' => $this->str('input_f12_deadlines'),
                        'value' => count($deadlines),
                    ],
                    ['key' => 'A', 'label' => $this->str('input_f12_a'), 'value' => round($a, 3)],
                    ['key' => 'B', 'label' => $this->str('input_f12_b'), 'value' => round($b, 3)],
                    ['key' => 'C', 'label' => $this->str('input_f12_c'), 'value' => round($c, 3)],
                ],
                'notes' => $this->str('notes_f12', $windowdays),
            ],
        ];
    }

    /**
     * Collect deadlines from common Moodle activities.
     */
    private function collect_deadlines(int $courseid, int $windowdays): array {
        global $DB;

        $since = time() - ($windowdays * DAYSECS);

        $deadlines = [];

        // Assign.
        if ($this->table_exists('assign')) {
            $sql = "SELECT duedate
                      FROM {assign} a
                      JOIN {course_modules} cm ON cm.instance = a.id
                     WHERE cm.course = :courseid
                       AND a.duedate > 0
                       AND a.duedate >= :since";
            $rows = $DB->get_records_sql($sql, ['courseid' => $courseid, 'since' => $since]);
            foreach ($rows as $r) {
                $deadlines[] = (int)$r->duedate;
            }
        }

        // Quiz.
        if ($this->table_exists('quiz')) {
            $sql = "SELECT timeclose
                      FROM {quiz} q
                      JOIN {course_modules} cm ON cm.instance = q.id
                     WHERE cm.course = :courseid
                       AND q.timeclose > 0
                       AND q.timeclose >= :since";
            $rows = $DB->get_records_sql($sql, ['courseid' => $courseid, 'since' => $since]);
            foreach ($rows as $r) {
                $deadlines[] = (int)$r->timeclose;
            }
        }

        // Lesson.
        if ($this->table_exists('lesson')) {
            $sql = "SELECT deadline
                      FROM {lesson} l
                      JOIN {course_modules} cm ON cm.instance = l.id
                     WHERE cm.course = :courseid
                       AND l.deadline > 0
                       AND l.deadline >= :since";
            $rows = $DB->get_records_sql($sql, ['courseid' => $courseid, 'since' => $since]);
            foreach ($rows as $r) {
                $deadlines[] = (int)$r->deadline;
            }
        }

        sort($deadlines);
        return $deadlines;
    }

    /**
     * A – Deadline density.
     */
    private function deadline_density(array $deadlines, int $windowdays): float {
        $density = count($deadlines) / max(1, $windowdays);

        // More than 0.25 deadlines per day (~2 per week) is stressful.
        return min(1.0, max(0.0, $density / 0.25));
    }

    /**
     * B – Deadline clustering.
     *
     * Measures how many deadlines fall on the same day.
     */
    private function deadline_clustering(array $deadlines): float {
        if (count($deadlines) < 2) {
            return 0.0;
        }

        $days = [];
        foreach ($deadlines as $ts) {
            $day = date('Y-m-d', $ts);
            $days[$day] = ($days[$day] ?? 0) + 1;
        }

        $max = max($days);

        // Three or more deadlines on one day indicate a panic cluster.
        return min(1.0, max(0.0, ($max - 1) / 2));
    }

    /**
     * C – Short-notice deadlines.
     *
     * Deadlines created close to their due date.
     */
    private function short_notice_deadlines(array $deadlines): float {
        $short = 0;
        $total = 0;

        foreach ($deadlines as $due) {
            $total++;

            // Use 3 days as "short notice".
            if (($due - time()) < (3 * DAYSECS)) {
                $short++;
            }
        }

        if ($total === 0) {
            return 0.0;
        }

        return min(1.0, max(0.0, $short / $total));
    }

    /**
     * Build the empty-result payload when no deadlines exist.
     *
     * @param int $windowdays Window size in days.
     * @return array Calculation result.
     */
    private function empty_result(int $windowdays): array {
        return [
            'score' => 0,
            'breakdown' => [
                'formula' => $this->str('formula_f12_empty'),
                'inputs' => [],
                'notes' => $this->str('notes_f12', $windowdays),
            ],
        ];
    }
}
