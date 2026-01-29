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
 * Interface for friction calculations.
 *
 * @package    coursereport_frictionradar
 */
interface friction_interface
{
    /**
     * Returns the friction key (e.g. 'f01').
     *
     * @return string
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
     *
     * @param int $courseid Course id.
     * @param int $windowdays Window size in days.
     * @return array Calculation result.
     */
    public function calculate(int $courseid, int $windowdays): array;
}
