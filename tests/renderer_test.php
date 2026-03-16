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

/**
 * Basic smoke tests for the renderer output.
 */
class renderer_test extends advanced_testcase
{
    public function test_export_for_template_sorts_top_issues_by_score(): void {
        $this->resetAfterTest(true);

        global $PAGE;
        $PAGE = new moodle_page();
        $PAGE->set_context(context_system::instance());

        /** @var \coursereport_frictionradar\output\renderer $renderer */
        $renderer = $PAGE->get_renderer('coursereport_frictionradar');

        $data = [
            'generated_at' => time(),
            'window_days' => 42,
            'overall' => 55,
            'analysis_mode' => \coursereport_frictionradar\local\analysis_mode::MODE_LIVE,
            'segments' => [
                'f01' => 12, 'f02' => 88, 'f03' => 34, 'f04' => 91, 'f05' => 67, 'f06' => 8,
                'f07' => 72, 'f08' => 51, 'f09' => 49, 'f10' => 15, 'f11' => 25, 'f12' => 35,
            ],
        ];

        $page = new \coursereport_frictionradar\output\friction_page(123, context_system::instance(), $data);
        $exported = $page->export_for_template($renderer);

        $this->assertSame('f04', $exported['topissues'][0]['key']);
        $this->assertSame('f02', $exported['topissues'][1]['key']);
        $this->assertSame('f07', $exported['topissues'][2]['key']);
        $this->assertSame('f04', $exported['indicators'][0]['key']);
    }

    public function test_renderer_outputs_prioritised_report_sections(): void {
        $this->resetAfterTest(true);

        $this->setAdminUser();

        global $PAGE;
        $PAGE = new moodle_page();
        $course = $this->getDataGenerator()->create_course();
        $context = context_course::instance($course->id);
        $PAGE->set_context($context);

        /** @var \coursereport_frictionradar\output\renderer $renderer */
        $renderer = $PAGE->get_renderer('coursereport_frictionradar');

        $data = [
            'generated_at' => time(),
            'window_days' => 42,
            'overall' => 55,
            'analysis_mode' => \coursereport_frictionradar\local\analysis_mode::MODE_STRUCTURAL,
            'segments' => [
                'f01' => 10, 'f02' => 20, 'f03' => 30, 'f04' => 40, 'f05' => null, 'f06' => null,
                'f07' => 70, 'f08' => 80, 'f09' => 90, 'f10' => 15, 'f11' => 25, 'f12' => 35,
            ],
            'breakdown' => [
                'f05' => ['status' => 'skipped', 'notes' => 'Skipped'],
                'f06' => ['status' => 'skipped', 'notes' => 'Skipped'],
            ],
        ];

        $renderable = new \coursereport_frictionradar\output\friction_page($course->id, $context, $data);
        $html = $renderer->render($renderable);

        $this->assertIsString($html);
        $this->assertStringContainsString('<svg', $html);
        $this->assertStringContainsString('viewBox="0 0 400 500"', $html);
        $this->assertStringContainsString('tool-frictionradar', $html);
        $this->assertStringContainsString('friction-detail-trigger', $html);
        $this->assertStringContainsString('Analysis mode', $html);
        $this->assertStringContainsString('Structural preview', $html);
        $this->assertStringContainsString('Top issues to review', $html);
        $this->assertStringContainsString('All indicators', $html);
        $this->assertStringContainsString('View details', $html);
        $this->assertStringContainsString('N/A', $html);
        $this->assertStringContainsString('<form method="post"', $html);
        $this->assertStringContainsString('name="sesskey"', $html);
    }
}
