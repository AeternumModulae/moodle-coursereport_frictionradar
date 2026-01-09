<?php
defined('MOODLE_INTERNAL') || die();

use tool_frictionradar\service\friction_cache;
use tool_frictionradar\task\queue_cache_warmers;
use tool_frictionradar\task\warm_course_cache;

/**
 * Tests for scheduled/adhoc tasks.
 */
class tool_frictionradar_tasks_test extends advanced_testcase {

    public function test_queue_cache_warmers_schedules_adhoc_tasks_for_visible_courses(): void {
        $this->resetAfterTest(true);
        global $DB;

        $generator = $this->getDataGenerator();
        $course1 = $generator->create_course(['visible' => 1]);
        $course2 = $generator->create_course(['visible' => 0]);

        // Run the scheduled task.
        $task = new queue_cache_warmers();
        $task->execute();

        // Adhoc tasks should be queued for visible courses only (excluding site course id=1).
        $records = $DB->get_records('task_adhoc', ['classname' => '\\tool_frictionradar\\task\\warm_course_cache']);
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
