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
 * @copyright  2026 Jan Svoboda <jan.svoboda@burml.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use coursereport_frictionradar\service\friction_cache;
use coursereport_frictionradar\task\queue_cache_warmers;
use coursereport_frictionradar\task\warm_course_cache;


/**
 * Tests for scheduled/adhoc tasks.
 */
class tasks_test extends advanced_testcase
{
    public function test_queue_cache_warmers_schedules_adhoc_tasks_for_visible_courses(): void {
        $this->resetAfterTest(true);
        global $DB;

        $generator = $this->getDataGenerator();
        $now = time();
        $course1 = $generator->create_course(['visible' => 1, 'startdate' => $now - DAYSECS]);
        $course2 = $generator->create_course(['visible' => 0, 'startdate' => $now - DAYSECS]);

        \core\event\course_viewed::create([
            'courseid' => $course1->id,
            'context' => \context_course::instance($course1->id),
        ])->trigger();
        \core\event\course_viewed::create([
            'courseid' => $course2->id,
            'context' => \context_course::instance($course2->id),
        ])->trigger();

        // Run the scheduled task.
        $task = new queue_cache_warmers();
        $task->execute();

        // Adhoc tasks should be queued for visible courses only (excluding site course id=1).
        $records = $DB->get_records(
            'task_adhoc',
            ['classname' => '\\coursereport_frictionradar\\task\\warm_course_cache']
        );
        $this->assertNotEmpty($records);

        $courseids = [];
        foreach ($records as $r) {
            $data = json_decode($r->customdata ?? '{}');
            if (isset($data->courseid)) {
                $courseids[] = (int)$data->courseid;
            }
        }

        $this->assertContains((int)$course1->id, $courseids);
        $this->assertNotContains((int)$course2->id, $courseids);
    }

    public function test_warm_course_cache_adhoc_task_populates_cache(): void {
        $this->resetAfterTest(true);

        $generator = $this->getDataGenerator();
        $course = $generator->create_course(['visible' => 1]);

        // Ensure empty.
        $this->assertNull(friction_cache::get_course($course->id));

        $task = new warm_course_cache();
        $task->set_custom_data(['courseid' => (int)$course->id]);
        $task->execute();

        $data = friction_cache::get_course($course->id);
        $this->assertNotNull($data);
        $this->assertIsArray($data);
        $this->assertArrayHasKey('segments', $data);
    }
}
