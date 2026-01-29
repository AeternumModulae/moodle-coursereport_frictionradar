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

namespace coursereport_frictionradar\service;


/**
 * Cache wrapper for friction score data.
 *
 * @package    coursereport_frictionradar
 */
class friction_cache
{
    /**
     * Warm the course cache for a course.
     *
     * @param int $courseid Course id.
     * @param int $windowdays Window size in days.
     */
    public static function warm_course(int $courseid, int $windowdays = 42): void {
        $cache = \cache::make('coursereport_frictionradar', 'course_friction_scores');
        $data = friction_calculator::calculate_course($courseid, $windowdays);
        $cache->set((string)$courseid, $data);
    }

    /**
     * Purge cached data for a course and recalculate it.
     *
     * @param int $courseid Course id.
     * @param int $windowdays Window size in days.
     */
    public static function refresh_course(int $courseid, int $windowdays = 42): void {
        $cache = \cache::make('coursereport_frictionradar', 'course_friction_scores');
        $cache->delete((string)$courseid);
        self::warm_course($courseid, $windowdays);
    }

    /**
     * Get cached course score data.
     *
     * @param int $courseid Course id.
     * @return array|null Score payload or null when missing.
     */
    public static function get_course(int $courseid): ?array {
        $cache = \cache::make('coursereport_frictionradar', 'course_friction_scores');
        $data = $cache->get((string)$courseid);
        return is_array($data) ? $data : null;
    }
}
