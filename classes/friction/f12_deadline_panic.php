<?php
namespace tool_frictionradar\friction;

defined('MOODLE_INTERNAL') || die();

/**
 * F12 – Deadline Panic / Deadline-Panik
 *
 * Measures temporal overload caused by clustered or short-term deadlines.
 */
class f12_deadline_panic extends abstract_friction {

    public function get_key(): string {
        return 'f12';
    }

    public function calculate(int $courseid, int $windowdays): array {

        $deadlines = $this->collect_deadlines($courseid, $windowdays);

        if (empty($deadlines)) {
            return $this->empty_result($windowdays);
        }

        $A = $this->deadline_density($deadlines, $windowdays); // 0..1
        $B = $this->deadline_clustering($deadlines);           // 0..1
        $C = $this->short_notice_deadlines($deadlines);        // 0..1

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
                    "A = deadline density\n" .
                    "B = deadline clustering\n" .
                    "C = short-notice deadlines",
                'inputs' => [
                    ['key'=>'deadlines','label'=>'Deadlines considered','value'=>count($deadlines)],
                    ['key'=>'A','label'=>'Deadline density (0..1)','value'=>round($A,3)],
                    ['key'=>'B','label'=>'Deadline clustering (0..1)','value'=>round($B,3)],
                    ['key'=>'C','label'=>'Short-notice ratio (0..1)','value'=>round($C,3)],
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

        // Assign
        $sql = "SELECT duedate
                  FROM {assign} a
                  JOIN {course_modules} cm ON cm.instance = a.id
                 WHERE cm.course = :courseid
                   AND a.duedate > 0
                   AND a.duedate >= :since";
        $rows = $DB->get_records_sql($sql, ['courseid'=>$courseid, 'since'=>$since]);
        foreach ($rows as $r) {
            $deadlines[] = (int)$r->duedate;
        }

        // Quiz
        $sql = "SELECT timeclose
                  FROM {quiz} q
                  JOIN {course_modules} cm ON cm.instance = q.id
                 WHERE cm.course = :courseid
                   AND q.timeclose > 0
                   AND q.timeclose >= :since";
        $rows = $DB->get_records_sql($sql, ['courseid'=>$courseid, 'since'=>$since]);
        foreach ($rows as $r) {
            $deadlines[] = (int)$r->timeclose;
        }

        // Lesson
        if ($DB->get_manager()->table_exists('lesson')) {
            $sql = "SELECT deadline
                      FROM {lesson} l
                      JOIN {course_modules} cm ON cm.instance = l.id
                     WHERE cm.course = :courseid
                       AND l.deadline > 0
                       AND l.deadline >= :since";
            $rows = $DB->get_records_sql($sql, ['courseid'=>$courseid, 'since'=>$since]);
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

        // >0.25 deadlines per day (~2 per week) is stressful.
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

        // ≥3 deadlines on one day = panic cluster.
        return min(1.0, max(0.0, ($max - 1) / 2));
    }

    /**
     * C – Short-notice deadlines.
     *
     * Deadlines created close to their due date.
     */
    private function short_notice_deadlines(array $deadlines): float {
        global $DB;

        $short = 0;
        $total = 0;

        foreach ($deadlines as $due) {
            $total++;

            // Use 3 days as "short notice"
            if (($due - time()) < (3 * DAYSECS)) {
                $short++;
            }
        }

        if ($total === 0) {
            return 0.0;
        }

        return min(1.0, max(0.0, $short / $total));
    }

    private function empty_result(int $windowdays): array {
        return [
            'score' => 0,
            'breakdown' => [
                'formula' => 'No deadlines detected.',
                'inputs' => [],
                'notes' => $this->str('notes_f12', $windowdays),
            ],
        ];
    }
}
