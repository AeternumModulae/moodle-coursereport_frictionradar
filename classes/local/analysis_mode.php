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
 * Analysis mode helper.
 *
 * @package    coursereport_frictionradar
 * @copyright  2026 Jan Svoboda <252793909+AeternumModulae@users.noreply.github.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace coursereport_frictionradar\local;

/**
 * Stable analysis mode values and helper methods.
 *
 * @package    coursereport_frictionradar
 */
class analysis_mode {
    /** Course analysis includes learner activity signals. */
    public const MODE_LIVE = 'live';

    /** Course analysis ignores learner activity signals. */
    public const MODE_STRUCTURAL = 'structural';

    /** Frictions that depend on learner-activity signals. */
    private const ACTIVITY_BASED_FRICTIONS = ['f05', 'f06'];

    /**
     * Return selectable mode options.
     *
     * @return array
     */
    public static function get_options(): array {
        return [
            self::MODE_LIVE => get_string('analysis_mode_live', 'coursereport_frictionradar'),
            self::MODE_STRUCTURAL => get_string('analysis_mode_structural', 'coursereport_frictionradar'),
        ];
    }

    /**
     * Return true when the provided mode is known.
     *
     * @param string $mode Mode value.
     * @return bool
     */
    public static function is_valid(string $mode): bool {
        return array_key_exists($mode, self::get_options());
    }

    /**
     * Normalize an arbitrary value to a supported mode.
     *
     * @param string|null $mode Raw mode value.
     * @return string
     */
    public static function normalize(?string $mode): string {
        if ($mode !== null && self::is_valid($mode)) {
            return $mode;
        }

        return self::MODE_LIVE;
    }

    /**
     * Return whether learner activity signals are included.
     *
     * @param string $mode Mode value.
     * @return bool
     */
    public static function includes_learner_activity(string $mode): bool {
        return self::normalize($mode) === self::MODE_LIVE;
    }

    /**
     * Return whether a friction key depends on learner activity.
     *
     * @param string $key Friction key.
     * @return bool
     */
    public static function is_activity_based_friction(string $key): bool {
        return in_array($key, self::ACTIVITY_BASED_FRICTIONS, true);
    }

    /**
     * Return a localized label for the mode.
     *
     * @param string $mode Mode value.
     * @return string
     */
    public static function get_label(string $mode): string {
        $mode = self::normalize($mode);
        return self::get_options()[$mode];
    }
}
