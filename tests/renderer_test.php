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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Friction Radar report.
 *
 * @package    coursereport_frictionradar
 * @copyright  2026 Jan Svoboda <jan.svoboda@bittra.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use context_system;
use moodle_page;


/**
 * Basic smoke tests for the renderer output.
 */
class renderer_test extends advanced_testcase
{
    public function test_renderer_outputs_svg_and_legend(): void {
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
            'segments' => [
                'f01' => 10, 'f02' => 20, 'f03' => 30, 'f04' => 40, 'f05' => 50, 'f06' => 60,
                'f07' => 70, 'f08' => 80, 'f09' => 90, 'f10' => 15, 'f11' => 25, 'f12' => 35,
            ],
        ];

        $html = $renderer->friction_clock($data, 123);

        $this->assertIsString($html);
        $this->assertStringContainsString('<svg', $html);
        $this->assertStringContainsString('viewBox="0 0 400 500"', $html);
        $this->assertStringContainsString('tool-frictionradar', $html);
        $this->assertStringContainsString('friction-info', $html);
    }
}
