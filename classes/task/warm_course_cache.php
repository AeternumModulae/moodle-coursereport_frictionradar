<?php
/**
 * Copyright (c) 2026 Jan Svoboda <jan.svoboda@bittra.de>
 * Project: Aeternum Modulae – https://aeternummodulae.com
 *
 * This file is part of the Aeternum Modulae Moodle plugin "Friction Radar".
 *
 * Licensed under the GNU General Public License v3.0 or later.
 * https://www.gnu.org/licenses/gpl-3.0.html
 */

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
