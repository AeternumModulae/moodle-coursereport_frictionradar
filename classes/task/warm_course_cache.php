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

use coursereport_frictionradar\service\friction_cache;


/**
 * Ad-hoc task for warming a single course cache.
 *
 * @package    coursereport_frictionradar
 */
class warm_course_cache extends \core\task\adhoc_task
{
    /**
     * Get the task name for display.
     *
     * @return string
     */
    public function get_name(): string {
        return get_string('task_warm_course', 'coursereport_frictionradar');
    }

    /**
     * Execute the task.
     */
    public function execute(): void {
        $data = $this->get_custom_data();
        $courseid = isset($data->courseid) ? (int)$data->courseid : 0;
        if ($courseid <= 1) {
            return;
        }
        friction_cache::warm_course($courseid, 42);
    }
}
