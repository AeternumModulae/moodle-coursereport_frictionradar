<?php
namespace tool_frictionradar\privacy;

defined('MOODLE_INTERNAL') || die();

class provider implements \core_privacy\local\metadata\provider {
    public static function get_metadata(\core_privacy\local\metadata\collection $collection): \core_privacy\local\metadata\collection {
        return $collection->add_external_location_link('cache', [], 'privacy:metadata');
    }
}
