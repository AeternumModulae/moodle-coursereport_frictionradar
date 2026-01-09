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
