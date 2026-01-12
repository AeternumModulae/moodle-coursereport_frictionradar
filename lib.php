/**
 * Copyright (c) 2026 Jan Svoboda <jan.svoboda@bittra.de>
 * Project: Aeternum Modulae – https://aeternummodulae.com
 *
 * This file is part of the Aeternum Modulae Moodle plugin "Friction Radar".
 *
 * Licensed under the GNU General Public License v3.0 or later.
 * https://www.gnu.org/licenses/gpl-3.0.html
 */

<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Add entry into the course settings navigation (Course administration).
 *
 * This is the most reliable place for course-leader tools.
 */
function tool_frictionradar_extend_settings_navigation(settings_navigation $settingsnav, context $context): void {
    if (!$context instanceof context_course) {
        return;
    }
    if (!has_capability('tool/frictionradar:view', $context)) {
        return;
    }

    $courseid = $context->instanceid;
    $url = new moodle_url('/admin/tool/frictionradar/index.php', ['id' => $courseid]);

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
    if ($parent->find('tool_frictionradar', navigation_node::TYPE_SETTING)) {
        return;
    }

    $parent->add(
        get_string('navitem', 'tool_frictionradar'),
        $url,
        navigation_node::TYPE_SETTING,
        null,
        'tool_frictionradar',
        new pix_icon('i/stats', '', 'core')
    );
}

/**
 * Fallback: Add to the course navigation tree.
 * Depending on theme, this may show up elsewhere, but it's a good backup.
 */
function tool_frictionradar_extend_navigation_course(navigation_node $navigation, stdClass $course, context_course $context): void {
    if (!has_capability('tool/frictionradar:view', $context)) {
        return;
    }

    $url = new moodle_url('/admin/tool/frictionradar/index.php', ['id' => $course->id]);

    // Try to attach to the course administration node if present.
    $courseadminnode = $navigation->find('courseadmin', navigation_node::TYPE_COURSE);
    $parent = $courseadminnode ?: $navigation;

    if ($parent->find('tool_frictionradar', navigation_node::TYPE_SETTING)) {
        return;
    }

    $parent->add(
        get_string('navitem', 'tool_frictionradar'),
        $url,
        navigation_node::TYPE_SETTING,
        null,
        'tool_frictionradar',
        new pix_icon('i/stats', '', 'core')
    );
}
