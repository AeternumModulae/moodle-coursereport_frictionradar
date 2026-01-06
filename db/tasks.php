<?php
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
