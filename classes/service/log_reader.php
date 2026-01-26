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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Friction Radar report.
 *
 * @package    coursereport_frictionradar
 * @copyright  2026 Jan Svoboda <jan.svoboda@bittra.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace coursereport_frictionradar\service;

use moodle_database;


/**
 * Wrapper for standard log queries used by frictions.
 *
 * @package    coursereport_frictionradar
 */
class log_reader
{
    /**
     * Database handle.
     *
     * @var moodle_database
     */
    private moodle_database $database;

    /**
     * Construct the log reader.
     *
     * @param moodle_database $db Database instance.
     */
    public function __construct(moodle_database $database) {
        $this->database = $database;
    }

    /**
     * Fetch module view events for a course within a time window.
     * Rows: userid, cmid (course_modules.id), timecreated.
     *
     * @param int $courseid Course id.
     * @param int $since Unix timestamp.
     * @return array Records.
     */
    public function get_module_views(int $courseid, int $since): array {
        $sql = "
            SELECT userid, contextinstanceid AS cmid, timecreated
              FROM {logstore_standard_log}
             WHERE courseid = :courseid
               AND contextlevel = :contextlevel
               AND timecreated >= :since
               AND action = :action
               AND target = :target
               AND userid > 0
             ORDER BY userid, timecreated
        ";
        $params = [
            'courseid' => $courseid,
            'contextlevel' => CONTEXT_MODULE,
            'since' => $since,
            'action' => 'viewed',
            'target' => 'course_module',
        ];
        return $this->database->get_records_sql($sql, $params);
    }

    /**
     * Fetch course viewed events.
     *
     * @param int $courseid Course id.
     * @param int $since Unix timestamp.
     * @return array Records.
     */
    public function get_course_views(int $courseid, int $since): array {
        $sql = "
            SELECT userid, timecreated
              FROM {logstore_standard_log}
             WHERE courseid = :courseid
               AND contextlevel = :contextlevel
               AND timecreated >= :since
               AND action = :action
               AND target = :target
               AND userid > 0
             ORDER BY userid, timecreated
        ";
        $params = [
            'courseid' => $courseid,
            'contextlevel' => CONTEXT_COURSE,
            'since' => $since,
            'action' => 'viewed',
            'target' => 'course',
        ];
        return $this->database->get_records_sql($sql, $params);
    }

    /**
     * Get all log timestamps for course (used for sessions / activity).
     *
     * @param int $courseid Course id.
     * @param int $since Unix timestamp.
     * @return array Records.
     */
    public function get_course_events(int $courseid, int $since): array {
        $sql = "
            SELECT userid, timecreated
              FROM {logstore_standard_log}
             WHERE courseid = :courseid
               AND timecreated >= :since
               AND userid > 0
             ORDER BY userid, timecreated
        ";
        return $this->database->get_records_sql($sql, ['courseid' => $courseid, 'since' => $since]);
    }
}
