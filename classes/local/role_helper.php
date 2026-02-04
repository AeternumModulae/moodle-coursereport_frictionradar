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

namespace coursereport_frictionradar\local;


/**
 * Role helper utilities.
 *
 * @package    coursereport_frictionradar
 */
class role_helper
{
    /**
     * Cached student-like role ids.
     *
     * @var int[]|null
     */
    private static $studentroleids = null;

    /**
     * Resolve student-like roles from plugin config or archetypes.
     *
     * @return int[] Role IDs.
     */
    public static function get_student_role_ids(): array {
        if (self::$studentroleids !== null) {
            return self::$studentroleids;
        }

        $roleids = [];
        $config = get_config('coursereport_frictionradar', 'studentroles');
        if (!empty($config)) {
            $parts = preg_split('/[\s,]+/', $config);
            foreach ($parts as $part) {
                $id = (int)$part;
                if ($id > 0) {
                    $roleids[] = $id;
                }
            }
        }

        if (empty($roleids)) {
            foreach (get_archetype_roles('student') as $role) {
                $roleids[] = (int)$role->id;
            }
            foreach (get_all_roles() as $role) {
                if ($role->shortname === 'student') {
                    $roleids[] = (int)$role->id;
                }
            }
        }

        $roleids = array_values(array_unique(array_filter($roleids)));
        self::$studentroleids = $roleids;
        return self::$studentroleids;
    }
}
