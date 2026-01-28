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
 * Friction Radar report.
 *
 * @package    coursereport_frictionradar
 * @copyright  2026 Jan Svoboda <jan.svoboda@bittra.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');

$courseid = required_param('id', PARAM_INT);
$course = get_course($courseid);
$context = context_course::instance($course->id);

require_login($course, false);
require_capability('coursereport/frictionradar:view', $context);

$PAGE->set_url(new moodle_url('/course/report/frictionradar/index.php', ['id' => $course->id]));
$PAGE->set_context($context);
$PAGE->set_course($course);
$PAGE->requires->css(new moodle_url('/course/report/frictionradar/styles.css'));
$PAGE->set_pagelayout('incourse');
$PAGE->set_title(get_string('page_title', 'coursereport_frictionradar'));
$PAGE->set_heading($course->fullname);

// Manual cache warm trigger (synchronous).
$warmcache = optional_param('warmcache', 0, PARAM_BOOL);
if ($warmcache) {
    require_sesskey();

    // Who is allowed to force a cache refresh?
    // Option A: Course editors (recommended).
    // If you prefer, replace with your own capability like 'coursereport/frictionradar:warmcache'.
    require_capability('moodle/course:update', $context);

    // Force-generate the cached values now.
    // NOTE: This must overwrite/refresh existing cache entries.
    \coursereport_frictionradar\service\friction_cache::warm_course($courseid);

    redirect(
        new moodle_url('/course/report/frictionradar/index.php', ['id' => $course->id]),
        get_string('warmcache_done', 'coursereport_frictionradar'),
        1,
        \core\output\notification::NOTIFY_SUCCESS
    );
}

echo $OUTPUT->header();

echo html_writer::tag('h2', get_string('page_title', 'coursereport_frictionradar'));
echo html_writer::div(get_string('page_subtitle', 'coursereport_frictionradar'), 'text-muted mb-1');

$data = \coursereport_frictionradar\service\friction_cache::get_course($courseid);

if (!$data) {
    $message = get_string('no_data', 'coursereport_frictionradar');

    // Add "Generate now" link for course editors.
    if (has_capability('moodle/course:update', $context)) {
        $url = new moodle_url('/course/report/frictionradar/index.php', [
            'id' => $course->id,
            'warmcache' => 1,
            'sesskey' => sesskey(),
        ]);

        $link = html_writer::link($url, get_string('warmcache_now', 'coursereport_frictionradar'));
        $message .= ' ' . $link;
    }

    echo html_writer::div($message, 'alert alert-info');
} else {
    /** @var \coursereport_frictionradar\output\renderer $renderer */
    $renderer = $PAGE->get_renderer('coursereport_frictionradar');
    echo $renderer->friction_clock($data, $courseid);
}

echo $OUTPUT->footer();
