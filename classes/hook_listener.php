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
 * Hook listeners for course edit integration.
 *
 * @package    coursereport_frictionradar
 * @copyright  2026 Jan Svoboda <252793909+AeternumModulae@users.noreply.github.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace coursereport_frictionradar;

use core_course\hook\after_form_definition;
use core_course\hook\after_form_submission;
use core_course\hook\after_form_validation;
use coursereport_frictionradar\local\analysis_mode;
use coursereport_frictionradar\local\course_settings;
use coursereport_frictionradar\service\friction_cache;

/**
 * Hook listeners for the course settings form.
 *
 * @package    coursereport_frictionradar
 */
class hook_listener {
    /** Form field name. */
    private const FIELD_ANALYSISMODE = 'coursereport_frictionradar_analysismode';

    /**
     * Extend the course settings form with the plugin section.
     *
     * @param after_form_definition $hook Hook payload.
     * @return void
     */
    public static function extend_course_form_definition(after_form_definition $hook): void {
        $mform = $hook->mform;
        $course = $hook->formwrapper->get_course();

        if ($mform->elementExists(self::FIELD_ANALYSISMODE)) {
            return;
        }

        $mform->addElement(
            'header',
            'coursereport_frictionradar_header',
            get_string('settingsheader', 'coursereport_frictionradar')
        );
        $mform->setExpanded('coursereport_frictionradar_header');

        $mform->addElement(
            'select',
            self::FIELD_ANALYSISMODE,
            get_string('analysis_mode', 'coursereport_frictionradar'),
            analysis_mode::get_options()
        );
        $mform->setType(self::FIELD_ANALYSISMODE, PARAM_ALPHANUMEXT);
        $mform->setDefault(
            self::FIELD_ANALYSISMODE,
            !empty($course->id) ? course_settings::get_mode((int)$course->id) : analysis_mode::MODE_LIVE
        );
        $mform->addHelpButton(
            self::FIELD_ANALYSISMODE,
            'analysis_mode',
            'coursereport_frictionradar'
        );
    }

    /**
     * Validate the submitted analysis mode.
     *
     * @param after_form_validation $hook Hook payload.
     * @return void
     */
    public static function validate_course_form(after_form_validation $hook): void {
        $data = $hook->get_data();
        $mode = $data[self::FIELD_ANALYSISMODE] ?? null;

        if ($mode !== null && !analysis_mode::is_valid((string)$mode)) {
            $hook->add_errors([
                self::FIELD_ANALYSISMODE => get_string('error_invalid_analysis_mode', 'coursereport_frictionradar'),
            ]);
        }
    }

    /**
     * Persist the selected analysis mode after the course form is submitted.
     *
     * @param after_form_submission $hook Hook payload.
     * @return void
     */
    public static function store_course_settings(after_form_submission $hook): void {
        $data = $hook->get_data();
        if (empty($data->id)) {
            return;
        }

        $changed = course_settings::save_mode(
            (int)$data->id,
            $data->{self::FIELD_ANALYSISMODE} ?? null
        );

        if ($changed) {
            friction_cache::refresh_course((int)$data->id);
        }
    }
}
