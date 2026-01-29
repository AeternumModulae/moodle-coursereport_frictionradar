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

namespace coursereport_frictionradar\friction;


/**
 * Base class for friction calculations.
 *
 * @package    coursereport_frictionradar
 */
abstract class abstract_friction implements friction_interface
{
    /**
     * Clamp a value between a minimum and maximum.
     *
     * @param int $value Value.
     * @param int $min Minimum.
     * @param int $max Maximum.
     * @return int Clamped value.
     */
    protected function clamp(int $value, int $min = 0, int $max = 100): int {
        return max($min, min($max, $value));
    }

    /**
     * Check whether a DB table exists (defensive for optional modules).
     *
     * @param string $tablename Table name without prefix.
     * @return bool
     */
    protected function table_exists(string $tablename): bool {
        global $DB;
        return $DB->get_manager()->table_exists($tablename);
    }

    /**
     * Convenience: fetch a language string for this plugin.
     *
     * @param string $key Language string key.
     * @param mixed $a Placeholder data.
     * @return string
     */
    protected function str(string $key, $a = null): string {
        return get_string($key, 'coursereport_frictionradar', $a);
    }
}
