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

namespace coursereport_frictionradar\task;


/**
 * Scheduled task that queues cache warming jobs.
 *
 * @package    coursereport_frictionradar
 */
class queue_cache_warmers extends \core\task\scheduled_task
{
    /**
     * Get the task name for display.
     *
     * @return string
     */
    public function get_name(): string {
        return get_string('task_warm_cache', 'coursereport_frictionradar');
    }

    /**
     * Execute the scheduled task.
     */
    public function execute(): void {
        global $DB;

        $now = time();
        $since = $now - (14 * DAYSECS);
        $courses = $DB->get_records_sql(
            "SELECT c.id, COALESCE(MAX(l.timecreated), 0) AS lastactivity
               FROM {course} c
          LEFT JOIN {logstore_standard_log} l
                 ON l.courseid = c.id
                AND l.timecreated >= :since
              WHERE visible = 1
                AND c.id <> 1
                AND c.startdate > 0
                AND c.startdate <= :nowstart
                AND (c.enddate = 0 OR c.enddate >= :nowend)
           GROUP BY c.id
           ORDER BY lastactivity DESC, c.id ASC",
            ['nowstart' => $now, 'nowend' => $now, 'since' => $since],
            0,
            500
        );

        foreach ($courses as $course) {
            $task = new warm_course_cache();
            $task->set_custom_data(['courseid' => (int)$course->id]);
            // Randomize next run time between 02:00 and 05:00 local server time.
            $next = self::random_nightly_timestamp(2, 5);
            $task->set_next_run_time($next);
            \core\task\manager::queue_adhoc_task($task);
        }
    }

    /**
     * Get a randomized timestamp between the provided hours.
     *
     * @param int $starthour Start hour (0-23).
     * @param int $endhour End hour (0-23).
     * @return int Unix timestamp.
     */
    private static function random_nightly_timestamp(int $starthour, int $endhour): int {
        // Use today's date; if already past endhour, schedule for next day.
        $now = time();
        $base = strtotime(date('Y-m-d', $now) . ' 00:00:00');
        $start = $base + ($starthour * 3600);
        $end = $base + ($endhour * 3600);
        if ($now > $end) {
            $base = strtotime(date('Y-m-d', $now + DAYSECS) . ' 00:00:00');
            $start = $base + ($starthour * 3600);
            $end = $base + ($endhour * 3600);
        }
        $rand = random_int($start, $end - 1);
        return $rand;
    }
}
