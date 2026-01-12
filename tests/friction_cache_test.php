/**
 * Copyright (c) 2026 Jan Svoboda <jan.svoboda@bittra.de>
 * Project: Aeternum Modulae – https://aeternummodulae.com
 *
 * This file is part of the Aeternum Modulae Moodle plugin "Friction Radar".
 *
 * Licensed under the GNU General Public License v3.0 or later.
 * https://www.gnu.org/licenses/gpl-3.0.html
 */

<?php
defined('MOODLE_INTERNAL') || die();

use tool_frictionradar\service\friction_cache;

/**
 * PHPUnit tests for cache wrapper.
 */
class tool_frictionradar_friction_cache_test extends advanced_testcase {

    public function test_warm_and_get_course_cache_roundtrip() {
        $this->resetAfterTest(true);

        $generator = $this->getDataGenerator();

        // Kurs explizit erzeugen
        $course = $generator->create_course();

        // Cache befüllen
        \tool_frictionradar\service\friction_cache::warm_course($course->id);

        // Cache abrufen
        $data = \tool_frictionradar\service\friction_cache::get_course($course->id);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('segments', $data);
        $this->assertArrayHasKey('overall', $data);
    }

    public function test_get_course_returns_null_when_empty(): void {
        $this->resetAfterTest(true);

        $generator = $this->getDataGenerator();
        $course = $generator->create_course(['visible' => 1]);

        $data = friction_cache::get_course($course->id);
        $this->assertNull($data);
    }
}
