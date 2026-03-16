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
        $context = \context_course::instance($course->id);

        $this->insert_course_view_log_record($DB, $course->id, $context->id, $user->id, time());
        $this->insert_course_view_log_record($DB, $course->id, $context->id, $user->id, time() + 1);

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
        $context = \context_course::instance($course->id);

        $this->insert_course_view_log_record($DB, $course->id, $context->id, $user->id, time());
        $this->insert_course_view_log_record($DB, $course->id, $context->id, $user->id, time() + 1);

        $reader = new log_reader($DB);
        $records = array_values($reader->get_course_events($course->id, 0));

        $this->assertCount(2, $records);
        $this->assertSame((int)$user->id, (int)$records[0]->userid);
        $this->assertSame((int)$user->id, (int)$records[1]->userid);
    }

    /**
     * Insert a standard-log course viewed event.
     *
     * @param moodle_database $db Database handle.
     * @param int $courseid Course id.
     * @param int $contextid Context id.
     * @param int $userid User id.
     * @param int $timecreated Event timestamp.
     * @return void
     */
    private function insert_course_view_log_record(
        moodle_database $db,
        int $courseid,
        int $contextid,
        int $userid,
        int $timecreated
    ): void {
        $db->insert_record('logstore_standard_log', (object)[
            'eventname' => '\core\event\course_viewed',
            'component' => 'core',
            'action' => 'viewed',
            'target' => 'course',
            'objecttable' => 'course',
            'objectid' => $courseid,
            'crud' => 'r',
            'edulevel' => \core\event\base::LEVEL_PARTICIPATING,
            'contextid' => $contextid,
            'contextlevel' => CONTEXT_COURSE,
            'contextinstanceid' => $courseid,
            'userid' => $userid,
            'courseid' => $courseid,
            'relateduserid' => 0,
            'anonymous' => 0,
            'other' => '',
            'timecreated' => $timecreated,
            'origin' => 'web',
            'ip' => '127.0.0.1',
            'realuserid' => null,
        ]);
    }
}
