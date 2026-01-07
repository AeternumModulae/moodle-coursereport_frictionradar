<?php
namespace tool_frictionradar\friction;

defined('MOODLE_INTERNAL') || die();

abstract class abstract_friction implements friction_interface {

    protected function clamp(int $value, int $min = 0, int $max = 100): int {
        return max($min, min($max, $value));
    }

    /**
     * Convenience: fetch a language string for this plugin.
     */
    protected function str(string $key, $a = null): string {
        return get_string($key, 'tool_frictionradar', $a);
    }
}
