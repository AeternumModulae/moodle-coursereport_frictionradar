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

namespace tool_frictionradar\friction;

defined('MOODLE_INTERNAL') || die();

abstract class abstract_friction implements friction_interface {

    protected function clamp(int $value, int $min = 0, int $max = 100): int {
        return max($min, min($max, $value));
    }

    /**
     * Convenience: fetch a language string for this plugin.
     */
    protected function str(string $key, $a = null): string {
        return get_string($key, 'tool_frictionradar', $a);
    }
}
