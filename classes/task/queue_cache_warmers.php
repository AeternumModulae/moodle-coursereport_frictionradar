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

namespace tool_frictionradar\task;

use core\task\adhoc_task;
use tool_frictionradar\task\warm_course_cache;

defined('MOODLE_INTERNAL') || die();

class queue_cache_warmers extends \core\task\scheduled_task {
    public function get_name(): string {
        return get_string('task_warm_cache', 'tool_frictionradar');
    }

    public function execute(): void {
        global $DB;

        $courses = $DB->get_records_sql(
            "SELECT id
               FROM {course}
              WHERE visible = 1
                AND id <> 1"
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

    private static function random_nightly_timestamp(int $starthour, int $endhour): int {
        // Use today's date; if already past endhour, schedule for next day.
        $now = time();
        $base = strtotime(date('Y-m-d', $now) . ' 00:00:00');
        $start = $base + ($starthour * 3600);
        $end = $base + ($endhour * 3600);
        if ($now > $end) {
            $base = strtotime(date('Y-m-d', $now + 86400) . ' 00:00:00');
            $start = $base + ($starthour * 3600);
            $end = $base + ($endhour * 3600);
        }
        $rand = random_int($start, $end - 1);
        return $rand;
    }
}
