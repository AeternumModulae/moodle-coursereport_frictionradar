<?php
namespace tool_frictionradar\friction;

defined('MOODLE_INTERNAL') || die();

interface friction_interface {
    /**
     * Returns the friction key (e.g. 'f01').
     */
    public function get_key(): string;

    /**
     * Calculate score + breakdown for a course.
     *
     * Return format:
     * [
     *   'score' => int 0..100,
     *   'breakdown' => [
     *      'formula' => string,
     *      'inputs'  => array of arrays (key/label/value),
     *      'notes'   => string,
     *   ]
     * ]
     */
    public function calculate(int $courseid, int $windowdays): array;
}
