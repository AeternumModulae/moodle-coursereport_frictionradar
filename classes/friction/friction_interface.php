<?php
/**
 * Copyright (c) 2026 Jan Svoboda <jan.svoboda@bittra.de>
 * Project: Aeternum Modulae – https://aeternummodulae.com
 *
 * This file is part of the Aeternum Modulae Moodle plugin "Friction Radar".
 *
 * Licensed under the GNU General Public License v3.0 or later.
 * https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace coursereport_frictionradar\friction;

defined('MOODLE_INTERNAL') || die();

interface friction_interface {
    /**
     * Returns the friction key (e.g. 'f01').
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
     */
    public function calculate(int $courseid, int $windowdays): array;
}
