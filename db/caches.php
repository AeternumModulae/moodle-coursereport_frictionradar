<?php
defined('MOODLE_INTERNAL') || die();

$definitions = [
    'course_friction_scores' => [
        'mode' => cache_store::MODE_APPLICATION,
        'simplekeys' => true,
        'simpledata' => true,
        'ttl' => 60 * 60 * 24 * 8, // Safety TTL, cache is warmed nightly.
        'staticacceleration' => true,
    ],
];
