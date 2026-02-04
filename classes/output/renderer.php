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

namespace coursereport_frictionradar\output;


/**
 * Renderer for the friction radar report.
 *
 * @package    coursereport_frictionradar
 */
class renderer extends \plugin_renderer_base
{
    /**
     * Render the friction report page.
     *
     * @param friction_page $page Page renderable.
     * @return string
     */
    public function render_friction_page(friction_page $page): string {
        $this->page->requires->js_call_amd(
            'coursereport_frictionradar/friction_info',
            'init'
        );

        return $this->render_from_template(
            'coursereport_frictionradar/friction_page',
            $page->export_for_template($this)
        );
    }
}
