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
 * Brand name helper.
 *
 * @package    coursereport_frictionradar
 * @copyright  2026 Jan Svoboda <jan.svoboda@burml.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace coursereport_frictionradar\local;

/**
 * Provides the display brand name for exposed UI locations.
 *
 * @package    coursereport_frictionradar
 */
class brand_name {
    /** The canonical English brand name. */
    private const ENGLISH_NAME = 'Friction Radar';

    /**
     * Return the display brand name for the current language.
     *
     * Latin-script locales keep the English brand. Selected Cyrillic and CJK
     * locales may use the translated plugin name instead.
     *
     * @return string
     */
    public static function get_display_name(): string {
        if (self::allows_localized_branding(current_language())) {
            return get_string('pluginname', 'coursereport_frictionradar');
        }

        return self::ENGLISH_NAME;
    }

    /**
     * Return whether the provided language may localize the brand name.
     *
     * @param string $language Language code.
     * @return bool
     */
    private static function allows_localized_branding(string $language): bool {
        $language = clean_param($language, PARAM_ALPHANUMEXT);

        $allowedprefixes = [
            'be',
            'bg',
            'ja',
            'kk',
            'ko',
            'ky',
            'mk',
            'mn',
            'ru',
            'sr',
            'tg',
            'tt',
            'uk',
            'zh',
        ];

        foreach ($allowedprefixes as $prefix) {
            if ($language === $prefix || strpos($language, $prefix . '_') === 0) {
                return true;
            }
        }

        return false;
    }
}
