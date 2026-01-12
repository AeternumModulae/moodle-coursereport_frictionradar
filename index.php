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
require_once(__DIR__ . '/../../../config.php');

use tool_frictionradar\service\friction_cache;

$courseid = required_param('id', PARAM_INT);
$course = get_course($courseid);
$context = context_course::instance($course->id);

require_login($course, false);
require_capability('tool/frictionradar:view', $context);

$PAGE->set_url(new moodle_url('/admin/tool/frictionradar/index.php', ['id' => $course->id]));
$PAGE->set_context($context);
$PAGE->set_course($course);
$PAGE->requires->css('/admin/tool/frictionradar/styles.css?time=202601050000');
$PAGE->set_pagelayout('incourse');
$PAGE->set_title(get_string('page_title', 'tool_frictionradar'));
$PAGE->set_heading($course->fullname);

// --- Manual cache warm trigger (synchronous) ---
$warmcache = optional_param('warmcache', 0, PARAM_BOOL);
if ($warmcache) {
    require_sesskey();

    // Who is allowed to force a cache refresh?
    // Option A: Course editors (recommended).
    // If you prefer, replace with your own capability like 'tool/frictionradar:warmcache'.
    require_capability('moodle/course:update', $context);

    // Force-generate the cached values now.
    // NOTE: This must overwrite/refresh existing cache entries.
    friction_cache::warm_course($courseid);

    redirect(
        new moodle_url('/admin/tool/frictionradar/index.php', ['id' => $course->id]),
        get_string('warmcache_done', 'tool_frictionradar'),
        1,
        \core\output\notification::NOTIFY_SUCCESS
    );
}

echo $OUTPUT->header();

echo html_writer::tag('h2', get_string('page_title', 'tool_frictionradar'));
echo html_writer::div(get_string('page_subtitle', 'tool_frictionradar'), 'text-muted mb-1');

$data = friction_cache::get_course($courseid);

if (!$data) {
    $message = get_string('no_data', 'tool_frictionradar');

    // Add "Generate now" link for course editors.
    if (has_capability('moodle/course:update', $context)) {
        $url = new moodle_url('/admin/tool/frictionradar/index.php', [
            'id' => $course->id,
            'warmcache' => 1,
            'sesskey' => sesskey(),
        ]);

        $link = html_writer::link($url, get_string('warmcache_now', 'tool_frictionradar'));
        $message .= ' ' . $link;
    }

    echo html_writer::div($message, 'alert alert-info');
} else {
    /** @var \tool_frictionradar\output\renderer $renderer */
    $renderer = $PAGE->get_renderer('tool_frictionradar');
    echo $renderer->friction_clock($data, $courseid);
}

echo $OUTPUT->footer();
