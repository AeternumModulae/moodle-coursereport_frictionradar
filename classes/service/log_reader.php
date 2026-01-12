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
namespace tool_frictionradar\service;

use moodle_database;

defined('MOODLE_INTERNAL') || die();

class log_reader {
    private moodle_database $db;

    public function __construct(moodle_database $db) {
        $this->db = $db;
    }

    /**
     * Fetch module view events for a course within a time window.
     * Rows: userid, cmid (course_modules.id), timecreated.
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
        return $this->db->get_records_sql($sql, $params);
    }

    /**
     * Fetch course viewed events.
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
        return $this->db->get_records_sql($sql, $params);
    }

    /**
     * Get all log timestamps for course (used for sessions / activity).
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
        return $this->db->get_records_sql($sql, ['courseid' => $courseid, 'since' => $since]);
    }
}
