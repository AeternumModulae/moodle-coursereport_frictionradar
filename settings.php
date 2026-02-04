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
 * Friction Radar report settings.
 *
 * @package    coursereport_frictionradar
 * @copyright  2026 Jan Svoboda <jan.svoboda@burml.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('coursereport_frictionradar', get_string('pluginname', 'coursereport_frictionradar'));
    $ADMIN->add('coursereports', $settings);

    if ($ADMIN->fulltree) {
        $context = \context_system::instance();
        $roles = get_all_roles();
        $options = [];
        foreach ($roles as $role) {
            $options[$role->id] = role_get_name($role, $context, ROLENAME_ORIGINAL);
        }

        $default = [];
        foreach (get_archetype_roles('student') as $role) {
            $default[] = (int)$role->id;
        }
        foreach ($roles as $role) {
            if ($role->shortname === 'student') {
                $default[] = (int)$role->id;
            }
        }
        $default = array_values(array_unique($default));

        $settings->add(new admin_setting_configmultiselect(
            'coursereport_frictionradar/studentroles',
            get_string('setting_studentroles', 'coursereport_frictionradar'),
            get_string('setting_studentroles_desc', 'coursereport_frictionradar'),
            $default,
            $options
        ));
    }
}
