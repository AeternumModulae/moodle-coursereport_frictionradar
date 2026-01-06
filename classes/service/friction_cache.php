<?php
namespace tool_frictionradar\service;

defined('MOODLE_INTERNAL') || die();

class friction_cache {
    public static function warm_course(int $courseid, int $windowdays = 42): void {
        $cache = \cache::make('tool_frictionradar', 'course_friction_scores');
        $data = friction_calculator::calculate_for_course($courseid, $windowdays);
        $cache->set((string)$courseid, $data);
    }

    public static function get_course(int $courseid): ?array {
        $cache = \cache::make('tool_frictionradar', 'course_friction_scores');
        $data = $cache->get((string)$courseid);
        return is_array($data) ? $data : null;
    }
}
