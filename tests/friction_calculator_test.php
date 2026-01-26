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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Friction Radar report.
 *
 * @package    coursereport_frictionradar
 * @copyright  2026 Jan Svoboda <jan.svoboda@bittra.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/**
 * PHPUnit tests for friction score calculation.
 */
class friction_calculator_test extends advanced_testcase
{
    /**
     * Insert a minimal logstore_standard_log record for tests.
     */
    private function insert_log(
        int $courseid,
        int $userid,
        int $contextlevel,
        int $contextinstanceid,
        string $action,
        string $target
    ): void {
        global $DB;

        if ($contextlevel === CONTEXT_COURSE) {
            $contextid = context_course::instance($courseid)->id;
        } else if ($contextlevel === CONTEXT_MODULE) {
            $contextid = context_module::instance($contextinstanceid)->id;
        } else {
            $contextid = context_system::instance()->id;
        }

        $record = (object)[
            // The calculator only filters on these core columns.
            'eventname' => '\\core\\event\\base',
            'component' => 'coursereport_frictionradar',
            'action' => $action,
            'target' => $target,
            'objecttable' => ($contextlevel === CONTEXT_MODULE ? 'course_modules' : 'course'),
            'objectid' => ($contextlevel === CONTEXT_MODULE ? $contextinstanceid : $courseid),
            'crud' => 'r',
            'edulevel' => \core\event\base::LEVEL_OTHER,
            'contextid' => $contextid,
            'contextlevel' => $contextlevel,
            'contextinstanceid' => $contextinstanceid,
            'userid' => $userid,
            'courseid' => $courseid,
            'relateduserid' => null,
            'anonymous' => 0,
            'other' => null,
            'timecreated' => time(),
            'origin' => 'web',
            'ip' => '127.0.0.1',
            'realuserid' => null,
        ];

        $DB->insert_record('logstore_standard_log', $record);
    }

    public function test_calculate_for_course_returns_expected_structure(): void {
        $this->resetAfterTest(true);

        $generator = $this->getDataGenerator();
        $course = $generator->create_course(['visible' => 1]);
        $user = $generator->create_user();
        $generator->enrol_user($user->id, $course->id, 'student');

        $data = \coursereport_frictionradar\service\friction_calculator::calculate_for_course($course->id, 7);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('generated_at', $data);
        $this->assertArrayHasKey('window_days', $data);
        $this->assertArrayHasKey('overall', $data);
        $this->assertArrayHasKey('segments', $data);
        $this->assertIsArray($data['segments']);

        $expected = ['f01', 'f02', 'f03', 'f04', 'f05', 'f06', 'f07', 'f08', 'f09', 'f10', 'f11', 'f12'];
        foreach ($expected as $k) {
            $this->assertArrayHasKey($k, $data['segments']);
            $this->assertIsInt($data['segments'][$k]);
            $this->assertGreaterThanOrEqual(0, $data['segments'][$k]);
            $this->assertLessThanOrEqual(100, $data['segments'][$k]);
        }

        $this->assertIsInt($data['overall']);
        $this->assertGreaterThanOrEqual(0, $data['overall']);
        $this->assertLessThanOrEqual(100, $data['overall']);
    }

    public function test_calculate_for_course_with_logs_is_still_stable(): void {
        $this->resetAfterTest(true);

        $generator = $this->getDataGenerator();
        $course = $generator->create_course(['visible' => 1]);
        $user = $generator->create_user();
        $generator->enrol_user($user->id, $course->id, 'student');

        // Create a visible activity so we get a real cmid + module context.
        $page = $generator->create_module('page', ['course' => $course->id, 'name' => 'Test page']);
        $cm = get_coursemodule_from_instance('page', $page->id, $course->id, false, MUST_EXIST);

        // Simulate a couple of course/module views.
        $this->insert_log($course->id, $user->id, CONTEXT_COURSE, $course->id, 'viewed', 'course');
        $this->insert_log($course->id, $user->id, CONTEXT_MODULE, (int)$cm->id, 'viewed', 'course_module');
        $this->insert_log($course->id, $user->id, CONTEXT_MODULE, (int)$cm->id, 'viewed', 'course_module');

        $data = \coursereport_frictionradar\service\friction_calculator::calculate_for_course($course->id, 7);
        $this->assertIsArray($data);
        $this->assertArrayHasKey('segments', $data);

        // We don't assert exact numbers (too many moving parts). We just expect a sane range.
        foreach ($data['segments'] as $score) {
            $this->assertIsInt($score);
            $this->assertGreaterThanOrEqual(0, $score);
            $this->assertLessThanOrEqual(100, $score);
        }
    }
}
