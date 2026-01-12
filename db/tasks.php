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

defined('MOODLE_INTERNAL') || die();

$tasks = [
    [
        'classname' => 'tool_frictionradar\\task\\queue_cache_warmers',
        'blocking'  => 0,
        'minute'    => '55',
        'hour'      => '1',
        'day'       => '*',
        'month'     => '*',
        'dayofweek' => '*',
    ],
];
