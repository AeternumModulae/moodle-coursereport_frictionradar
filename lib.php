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
 * Friction Radar report.
 *
 * @package    coursereport_frictionradar
 * @copyright  2026 Jan Svoboda <jan.svoboda@burml.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Add entry into the course settings navigation (Course administration).
 *
 * This is the most reliable place for course-leader tools.
 *
 * @param settings_navigation $settingsnav Settings navigation tree.
 * @param context $context Current context.
 */
function coursereport_frictionradar_extend_settings_navigation(settings_navigation $settingsnav, context $context): void {
    if (!$context instanceof context_course) {
        return;
    }
    if (!has_capability('coursereport/frictionradar:view', $context)) {
        return;
    }

    $courseid = $context->instanceid;
    $url = new moodle_url('/course/report/frictionradar/index.php', ['id' => $courseid]);

    // Try several possible parent nodes, because Moodle/themes can vary.
    $parents = [];

    // Standard in many Moodle builds.
    $parents[] = $settingsnav->find('courseadmin', navigation_node::TYPE_COURSE);

    // Sometimes courseadmin is a container node.
    $parents[] = $settingsnav->find('courseadmin', navigation_node::TYPE_CONTAINER);

    // In some themes, the settings root is used.
    $parents[] = $settingsnav;

    $parent = null;
    foreach ($parents as $p) {
        if ($p instanceof navigation_node) {
            $parent = $p;
            break;
        }
    }

    if (!$parent) {
        return;
    }

    // Avoid duplicate nodes.
    if ($parent->find('coursereport_frictionradar', navigation_node::TYPE_SETTING)) {
        return;
    }

    $parent->add(
        get_string('navitem', 'coursereport_frictionradar'),
        $url,
        navigation_node::TYPE_SETTING,
        null,
        'coursereport_frictionradar',
        new pix_icon('i/stats', '', 'core')
    );
}

/**
 * Fallback: Add to the course navigation tree.
 * Depending on theme, this may show up elsewhere, but it's a good backup.
 *
 * @param navigation_node $navigation Course navigation tree.
 * @param stdClass $course Course record.
 * @param context_course $context Course context.
 */
function coursereport_frictionradar_extend_navigation_course(
    navigation_node $navigation,
    stdClass $course,
    context_course $context
): void {
    if (!has_capability('coursereport/frictionradar:view', $context)) {
        return;
    }

    $url = new moodle_url('/course/report/frictionradar/index.php', ['id' => $course->id]);

    // Try to attach to the course administration node if present.
    $courseadminnode = $navigation->find('courseadmin', navigation_node::TYPE_COURSE);
    $parent = $courseadminnode ?: $navigation;

    if ($parent->find('coursereport_frictionradar', navigation_node::TYPE_SETTING)) {
        return;
    }

    $parent->add(
        get_string('navitem', 'coursereport_frictionradar'),
        $url,
        navigation_node::TYPE_SETTING,
        null,
        'coursereport_frictionradar',
        new pix_icon('i/stats', '', 'core')
    );
}
