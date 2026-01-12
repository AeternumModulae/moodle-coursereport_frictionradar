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
defined('MOODLE_INTERNAL') || die();

/**
 * Basic smoke tests for the renderer output.
 */
class tool_frictionradar_renderer_test extends advanced_testcase {

    public function test_renderer_outputs_svg_and_legend(): void {
        $this->resetAfterTest(true);

        global $PAGE;
        $PAGE = new moodle_page();
        $PAGE->set_context(context_system::instance());

        /** @var \tool_frictionradar\output\renderer $renderer */
        $renderer = $PAGE->get_renderer('tool_frictionradar');

        $data = [
            'generated_at' => time(),
            'window_days' => 42,
            'overall' => 55,
            'segments' => [
                'f01'=>10,'f02'=>20,'f03'=>30,'f04'=>40,'f05'=>50,'f06'=>60,
                'f07'=>70,'f08'=>80,'f09'=>90,'f10'=>15,'f11'=>25,'f12'=>35,
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
