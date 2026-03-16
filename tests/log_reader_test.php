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
 * Tests for the log reader helper.
 *
 * @package    coursereport_frictionradar
 * @copyright  2026 Jan Svoboda <jan.svoboda@burml.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use coursereport_frictionradar\service\log_reader;

/**
 * Tests for log reader event retrieval.
 */
final class log_reader_test extends advanced_testcase {
    public function test_get_course_views_keeps_multiple_rows_for_same_user(): void {
        $this->resetAfterTest(true);

        global $DB;

        $generator = $this->getDataGenerator();
        $course = $generator->create_course();
        $user = $generator->create_user();
        $this->setUser($user);

        \core\event\course_viewed::create([
            'courseid' => $course->id,
            'context' => \context_course::instance($course->id),
        ])->trigger();
        \core\event\course_viewed::create([
            'courseid' => $course->id,
            'context' => \context_course::instance($course->id),
        ])->trigger();

        $reader = new log_reader($DB);
        $records = array_values($reader->get_course_views($course->id, 0));

        $this->assertCount(2, $records);
        $this->assertSame((int)$user->id, (int)$records[0]->userid);
        $this->assertSame((int)$user->id, (int)$records[1]->userid);
    }

    public function test_get_course_events_keeps_multiple_rows_for_same_user(): void {
        $this->resetAfterTest(true);

        global $DB;

        $generator = $this->getDataGenerator();
        $course = $generator->create_course();
        $user = $generator->create_user();
        $this->setUser($user);

        \core\event\course_viewed::create([
            'courseid' => $course->id,
            'context' => \context_course::instance($course->id),
        ])->trigger();
        \core\event\course_viewed::create([
            'courseid' => $course->id,
            'context' => \context_course::instance($course->id),
        ])->trigger();

        $reader = new log_reader($DB);
        $records = array_values($reader->get_course_events($course->id, 0));

        $this->assertCount(2, $records);
        $this->assertSame((int)$user->id, (int)$records[0]->userid);
        $this->assertSame((int)$user->id, (int)$records[1]->userid);
    }
}
