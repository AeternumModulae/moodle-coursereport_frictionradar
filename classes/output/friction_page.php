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

use renderer_base;
use renderable;
use templatable;


/**
 * Renderable friction report page.
 *
 * @package    coursereport_frictionradar
 */
class friction_page implements renderable, templatable
{
    /** @var int */
    private $courseid;

    /** @var \context */
    private $context;

    /** @var array|null */
    private $data;

    /**
     * Constructor.
     *
     * @param int $courseid Course id.
     * @param \context $context Context.
     * @param array|null $data Cached friction data.
     */
    public function __construct(int $courseid, \context $context, ?array $data) {
        $this->courseid = $courseid;
        $this->context = $context;
        $this->data = $data;
    }

    /**
     * Export data for the template.
     *
     * @param renderer_base $output Renderer.
     * @return array
     */
    public function export_for_template(renderer_base $output): array {
        $hasdata = !empty($this->data);
        $canrefresh = has_capability('moodle/course:update', $this->context);
        $refreshlabel = get_string('warmcache_now', 'coursereport_frictionradar');
        $refreshurl = new \moodle_url('/course/report/frictionradar/index.php', [
            'id' => $this->courseid,
            'warmcache' => 1,
            'sesskey' => sesskey(),
        ]);

        $segments = $this->data['segments'] ?? [];
        $breakdown = $this->data['breakdown'] ?? [];
        $generated = (int)($this->data['generated_at'] ?? 0);
        $window = (int)($this->data['window_days'] ?? 42);

        $order = ['f01', 'f02', 'f03', 'f04', 'f05', 'f06', 'f07', 'f08', 'f09', 'f10', 'f11', 'f12'];

        $iconmap = [
            'f01' => 'frictions/F01_cognitive_overload',
            'f02' => 'frictions/F02_didactic_pitfalls',
            'f03' => 'frictions/F03_navigation_chaos',
            'f04' => 'frictions/F04_overambitious_entry',
            'f05' => 'frictions/F05_participation_theatre',
            'f06' => 'frictions/F06_zombie_quizzes',
            'f07' => 'frictions/F07_unclear_expectations',
            'f08' => 'frictions/F08_structure_paradox',
            'f09' => 'frictions/F09_resource_overload',
            'f10' => 'frictions/F10_hidden_dependencies',
            'f11' => 'frictions/F11_frust_scroll',
            'f12' => 'frictions/F12_deadline_panic',
        ];

        $legenditems = [];
        $uiscore   = get_string('ui_score', 'coursereport_frictionradar');
        $uiformula = get_string('ui_formula', 'coursereport_frictionradar');
        $uiinputs  = get_string('ui_inputs', 'coursereport_frictionradar');
        $uiparam   = get_string('ui_param', 'coursereport_frictionradar');
        $uivalue   = get_string('ui_value', 'coursereport_frictionradar');
        $uinotes   = get_string('ui_notes', 'coursereport_frictionradar');

        $explanations = [
            'f01' => get_string('explain_f01', 'coursereport_frictionradar'),
            'f02' => get_string('explain_f02', 'coursereport_frictionradar'),
            'f03' => get_string('explain_f03', 'coursereport_frictionradar'),
            'f04' => get_string('explain_f04', 'coursereport_frictionradar'),
            'f05' => get_string('explain_f05', 'coursereport_frictionradar'),
            'f06' => get_string('explain_f06', 'coursereport_frictionradar'),
            'f07' => get_string('explain_f07', 'coursereport_frictionradar'),
            'f08' => get_string('explain_f08', 'coursereport_frictionradar'),
            'f09' => get_string('explain_f09', 'coursereport_frictionradar'),
            'f10' => get_string('explain_f10', 'coursereport_frictionradar'),
            'f11' => get_string('explain_f11', 'coursereport_frictionradar'),
            'f12' => get_string('explain_f12', 'coursereport_frictionradar'),
        ];

        $actions = [
            'f01' => get_string('action_f01', 'coursereport_frictionradar'),
            'f02' => get_string('action_f02', 'coursereport_frictionradar'),
            'f03' => get_string('action_f03', 'coursereport_frictionradar'),
            'f04' => get_string('action_f04', 'coursereport_frictionradar'),
            'f05' => get_string('action_f05', 'coursereport_frictionradar'),
            'f06' => get_string('action_f06', 'coursereport_frictionradar'),
            'f07' => get_string('action_f07', 'coursereport_frictionradar'),
            'f08' => get_string('action_f08', 'coursereport_frictionradar'),
            'f09' => get_string('action_f09', 'coursereport_frictionradar'),
            'f10' => get_string('action_f10', 'coursereport_frictionradar'),
            'f11' => get_string('action_f11', 'coursereport_frictionradar'),
            'f12' => get_string('action_f12', 'coursereport_frictionradar'),
        ];

        foreach ($order as $key) {
            $score = (int)($segments[$key] ?? 0);
            $bd = $breakdown[$key] ?? [];
            $formula = $bd['formula'] ?? '';
            $inputsjson = json_encode($bd['inputs'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $notes = $bd['notes'] ?? '';

            $legenditems[] = [
                'key' => $key,
                'label' => get_string('friction_' . $key, 'coursereport_frictionradar'),
                'score' => $score,
                'color' => $this->color_for_score($score),
                'iconurl' => isset($iconmap[$key])
                    ? $output->image_url($iconmap[$key], 'coursereport_frictionradar')->out(false)
                    : null,
                'explanation' => $explanations[$key] ?? '',
                'what_to_do' => get_string('what_to_do', 'coursereport_frictionradar'),
                'action' => $actions[$key] ?? '',
                'formula' => $formula,
                'inputsjson' => $inputsjson,
                'notes' => $notes,
                'ui_score' => $uiscore,
                'ui_formula' => $uiformula,
                'ui_inputs' => $uiinputs,
                'ui_param' => $uiparam,
                'ui_value' => $uivalue,
                'ui_notes' => $uinotes,
            ];
        }

        return [
            'page_title' => get_string('page_title', 'coursereport_frictionradar'),
            'page_subtitle' => get_string('page_subtitle', 'coursereport_frictionradar'),
            'hasdata' => $hasdata,
            'nodata' => get_string('no_data', 'coursereport_frictionradar'),
            'canrefresh' => $canrefresh,
            'refreshurl' => $refreshurl->out(false),
            'refreshlabel' => $refreshlabel,
            'svg' => $hasdata ? $this->build_svg($output, $segments, $order, $iconmap) : '',
            'overall' => $overall,
            'showmeta' => ($generated > 0),
            'generated_label' => get_string('generated_at', 'coursereport_frictionradar'),
            'generated_at' => $generated > 0 ? userdate($generated) : '',
            'window_label' => get_string('window', 'coursereport_frictionradar'),
            'window_days' => $window,
            'days_label' => get_string('days', 'coursereport_frictionradar'),
            'legenditems' => $legenditems,
            'svgtitle' => get_string('friction_clock_aria', 'coursereport_frictionradar'),
            'svgtitleid' => 'frictionradar-title-' . $this->courseid,
        ];
    }

    /**
     * Build the SVG for the friction clock.
     *
     * @param renderer_base $output Renderer.
     * @param array $segments Segment scores.
     * @param array $order Segment order.
     * @param array $iconmap Icon map.
     * @return string
     */
    private function build_svg(renderer_base $output, array $segments, array $order, array $iconmap): string {
        $cx = 200;
        $cy = 250;
        $router = 200;
        $rinner = 120;
        $startangle = -90;
        $delta = 360 / 12;

        $paths = '';

        for ($i = 0; $i < 12; $i++) {
            $key = $order[$i];
            $score = (int)($segments[$key] ?? 0);

            $a1 = deg2rad($startangle + $i * $delta);
            $a2 = deg2rad($startangle + ($i + 1) * $delta);

            $path = $this->donut_segment_path($cx, $cy, $router, $rinner, $a1, $a2);
            $fill = $this->color_for_score($score);

            $paths .= '<path d="' . $path . '" fill="' . $fill . '" stroke="#E5E7EB" stroke-width="1"/>';

            if (isset($iconmap[$key])) {
                $iconurl = $output->image_url($iconmap[$key], 'coursereport_frictionradar')->out(false);

                $mid = deg2rad($startangle + ($i + 0.5) * $delta);
                $ricon = ($router + $rinner) / 2;

                $ix = $cx + cos($mid) * $ricon;
                $iy = $cy + sin($mid) * $ricon;

                $iconsize = 48;

                $paths .= '<image href="' . s($iconurl) . '"'
                    . ' x="' . ($ix - $iconsize / 2) . '"'
                    . ' y="' . ($iy - $iconsize / 2) . '"'
                    . ' width="' . $iconsize . '"'
                    . ' height="' . $iconsize . '"'
                    . ' preserveAspectRatio="xMidYMid meet"'
                    . ' />';
            }
        }

        $center  = '<circle cx="' . $cx . '" cy="' . $cy . '" r="95" fill="#FFFFFF"'
            . ' stroke="#E5E7EB" stroke-width="2" />';
        $center .= '<text x="' . $cx . '" y="' . ($cy - 18) . '" text-anchor="middle"
            font-family="system-ui, -apple-system, Segoe UI, Roboto, Arial"
            font-size="14" fill="#6B7280">'
            . s(get_string('overall_score', 'coursereport_frictionradar')) .
        '</text>';
        $center .= '<text x="' . $cx . '" y="' . ($cy + 28) . '" text-anchor="middle"
            font-family="system-ui, -apple-system, Segoe UI, Roboto, Arial"
            font-size="56" font-weight="700" fill="#111827">'
            . (int)($this->data['overall'] ?? 0) .
        '</text>';

        $svgtitle = s(get_string('friction_clock_aria', 'coursereport_frictionradar'));
        $svgtitleid = 'frictionradar-title-' . $this->courseid;

        return '<svg style="max-width: 520px;" viewBox="0 0 400 500"
            width="100%"
            preserveAspectRatio="xMidYMid meet"
            xmlns="http://www.w3.org/2000/svg"
            role="img"
            aria-labelledby="' . s($svgtitleid) . '">'
            . '<title id="' . s($svgtitleid) . '">' . $svgtitle . '</title>'
            . $paths
            . $center
            . '</svg>';
    }

    /**
     * Score-to-color mapping.
     *
     * @param int $score Score.
     * @return string
     */
    private function color_for_score(int $score): string {
        if ($score <= 15) {
            return '#E6EEF6';
        }
        if ($score <= 30) {
            return '#C9DBEE';
        }
        if ($score <= 50) {
            return '#9FBAD6';
        }
        if ($score <= 70) {
            return '#6F8FB3';
        }
        if ($score <= 85) {
            return '#3F658F';
        }
        return '#1F3A5F';
    }

    /**
     * Build an SVG path for a donut segment.
     *
     * @param float $cx Center x.
     * @param float $cy Center y.
     * @param float $router Outer radius.
     * @param float $rinner Inner radius.
     * @param float $a1 Start angle in radians.
     * @param float $a2 End angle in radians.
     * @return string SVG path.
     */
    private function donut_segment_path(
        float $cx,
        float $cy,
        float $router,
        float $rinner,
        float $a1,
        float $a2
    ): string {
        $largearc = (($a2 - $a1) > M_PI) ? 1 : 0;

        $x1 = $cx + $router * cos($a1);
        $y1 = $cy + $router * sin($a1);
        $x2 = $cx + $router * cos($a2);
        $y2 = $cy + $router * sin($a2);

        $x3 = $cx + $rinner * cos($a2);
        $y3 = $cy + $rinner * sin($a2);
        $x4 = $cx + $rinner * cos($a1);
        $y4 = $cy + $rinner * sin($a1);

        return sprintf(
            'M %.3f %.3f A %.3f %.3f 0 %d 1 %.3f %.3f
             L %.3f %.3f
             A %.3f %.3f 0 %d 0 %.3f %.3f Z',
            $x1,
            $y1,
            $router,
            $router,
            $largearc,
            $x2,
            $y2,
            $x3,
            $y3,
            $rinner,
            $rinner,
            $largearc,
            $x4,
            $y4
        );
    }
}
