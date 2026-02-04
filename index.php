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
 * @copyright  2026 Jan Svoboda <jan.svoboda@burml.com>
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
    \coursereport_frictionradar\service\friction_cache::refresh_course($courseid);

    redirect(
        new moodle_url('/course/report/frictionradar/index.php', ['id' => $course->id]),
        get_string('warmcache_done', 'coursereport_frictionradar'),
        1,
        \core\output\notification::NOTIFY_SUCCESS
    );
}

echo $OUTPUT->header();

$data = \coursereport_frictionradar\service\friction_cache::get_course($courseid);

/** @var \coursereport_frictionradar\output\renderer $renderer */
$renderer = $PAGE->get_renderer('coursereport_frictionradar');
$renderable = new \coursereport_frictionradar\output\friction_page($courseid, $context, $data);
echo $renderer->render($renderable);

echo $OUTPUT->footer();
