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


/**
 * PHPUnit tests for cache wrapper.
 */
class friction_cache_test extends advanced_testcase
{
    public function test_warm_and_get_course_cache_roundtrip() {
        $this->resetAfterTest(true);

        $generator = $this->getDataGenerator();

        // Kurs explizit erzeugen.
        $course = $generator->create_course();

        // Cache befuellen.
        \coursereport_frictionradar\service\friction_cache::warm_course($course->id);

        // Cache abrufen.
        $data = \coursereport_frictionradar\service\friction_cache::get_course($course->id);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('segments', $data);
        $this->assertArrayHasKey('overall', $data);
    }

    public function test_get_course_returns_null_when_empty(): void {
        $this->resetAfterTest(true);

        $generator = $this->getDataGenerator();
        $course = $generator->create_course(['visible' => 1]);

        $data = \coursereport_frictionradar\service\friction_cache::get_course($course->id);
        $this->assertNull($data);
    }
}
