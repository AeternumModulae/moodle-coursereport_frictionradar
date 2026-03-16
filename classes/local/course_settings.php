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
 * Per-course Friction Radar settings.
 *
 * @package    coursereport_frictionradar
 * @copyright  2026 Jan Svoboda <252793909+AeternumModulae@users.noreply.github.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace coursereport_frictionradar\local;

/**
 * Persistence wrapper for course-specific Friction Radar settings.
 *
 * @package    coursereport_frictionradar
 */
class course_settings {
    /** Course settings table name. */
    private const TABLE = 'coursereport_frictionradar';

    /**
     * Return the current analysis mode for a course.
     *
     * @param int $courseid Course id.
     * @return string
     */
    public static function get_mode(int $courseid): string {
        global $DB;

        $mode = $DB->get_field(self::TABLE, 'analysismode', ['courseid' => $courseid], IGNORE_MISSING);
        return analysis_mode::normalize($mode ?: null);
    }

    /**
     * Persist the analysis mode for a course.
     *
     * Returns true when the stored mode changed.
     *
     * @param int $courseid Course id.
     * @param string|null $mode Submitted mode value.
     * @return bool
     */
    public static function save_mode(int $courseid, ?string $mode): bool {
        global $DB;

        $normalizedmode = analysis_mode::normalize($mode);
        $existing = $DB->get_record(self::TABLE, ['courseid' => $courseid]);
        $now = time();

        if ($existing) {
            if ($normalizedmode === analysis_mode::MODE_LIVE) {
                $DB->delete_records(self::TABLE, ['id' => $existing->id]);
                return $existing->analysismode !== analysis_mode::MODE_LIVE;
            }

            if ($existing->analysismode === $normalizedmode) {
                return false;
            }

            $existing->analysismode = $normalizedmode;
            $existing->timemodified = $now;
            $DB->update_record(self::TABLE, $existing);
            return true;
        }

        if ($normalizedmode === analysis_mode::MODE_LIVE) {
            return false;
        }

        $record = (object)[
            'courseid' => $courseid,
            'analysismode' => $normalizedmode,
            'timecreated' => $now,
            'timemodified' => $now,
        ];
        $DB->insert_record(self::TABLE, $record);

        return true;
    }
}
