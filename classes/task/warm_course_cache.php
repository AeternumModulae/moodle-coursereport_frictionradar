<?php
namespace tool_frictionradar\task;

use tool_frictionradar\service\friction_cache;

defined('MOODLE_INTERNAL') || die();

class warm_course_cache extends \core\task\adhoc_task {
    public function get_name(): string {
        return get_string('task_warm_course', 'tool_frictionradar');
    }

    public function execute(): void {
        $data = $this->get_custom_data();
        $courseid = isset($data->courseid) ? (int)$data->courseid : 0;
        if ($courseid <= 1) {
            return;
        }
        friction_cache::warm_course($courseid, 42);
    }
}
