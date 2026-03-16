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
 * PHPUnit tests for per-course settings.
 *
 * @package    coursereport_frictionradar
 * @copyright  2026 Jan Svoboda <252793909+AeternumModulae@users.noreply.github.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Tests for course settings persistence.
 */
class course_settings_test extends advanced_testcase {
    public function test_get_mode_defaults_to_live(): void {
        $this->resetAfterTest(true);

        $course = $this->getDataGenerator()->create_course();

        $this->assertSame(
            \coursereport_frictionradar\local\analysis_mode::MODE_LIVE,
            \coursereport_frictionradar\local\course_settings::get_mode($course->id)
        );
    }

    public function test_save_mode_persists_structural_mode(): void {
        $this->resetAfterTest(true);

        $course = $this->getDataGenerator()->create_course();

        $changed = \coursereport_frictionradar\local\course_settings::save_mode(
            $course->id,
            \coursereport_frictionradar\local\analysis_mode::MODE_STRUCTURAL
        );

        $this->assertTrue($changed);
        $this->assertSame(
            \coursereport_frictionradar\local\analysis_mode::MODE_STRUCTURAL,
            \coursereport_frictionradar\local\course_settings::get_mode($course->id)
        );
    }
}
