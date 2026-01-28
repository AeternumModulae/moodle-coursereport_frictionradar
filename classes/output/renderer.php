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

namespace coursereport_frictionradar\output;

use html_writer;


/**
 * Renderer for the friction radar report.
 *
 * @package    coursereport_frictionradar
 */
class renderer extends \plugin_renderer_base
{
    /**
     * Render the friction clock SVG with legend.
     *
     * @param array $data Score payload.
     * @param int $courseid Course id.
     * @return string Rendered HTML.
     */
    public function friction_clock(array $data, int $courseid): string {
        $segments  = $data['segments'] ?? [];
        $overall   = (int)($data['overall'] ?? 0);
        $generated = (int)($data['generated_at'] ?? 0);
        $window    = (int)($data['window_days'] ?? 42);

        // Color palette.
        $colors = function (int $score): string {
            if ($score <= 15) {
                return '#E6EEF6'; // Mist Blue.
            }
            if ($score <= 30) {
                return '#C9DBEE'; // Ice Blue.
            }
            if ($score <= 50) {
                return '#9FBAD6'; // Steel Blue Light.
            }
            if ($score <= 70) {
                return '#6F8FB3'; // Slate Blue.
            }
            if ($score <= 85) {
                return '#3F658F'; // Deep Steel Blue.
            }
            return '#1F3A5F'; // Midnight Blue.
        };

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

        // SVG geometry.
        $cx = 200;
        $cy = 250;
        $router = 200;
        $rinner = 120;
        $startangle = -90;
        $delta = 360 / 12;

        $paths = '';

        for ($i = 0; $i < 12; $i++) {
            $k = $order[$i];
            $score = (int)($segments[$k] ?? 0);

            $a1 = deg2rad($startangle + $i * $delta);
            $a2 = deg2rad($startangle + ($i + 1) * $delta);

            $p = $this->donut_segment_path($cx, $cy, $router, $rinner, $a1, $a2);
            $fill = $colors($score);

            // Segment.
            $paths .= '<path d="' . $p . '" fill="' . $fill . '" stroke="#E5E7EB" stroke-width="1"/>';

            // Icon.
            if (isset($iconmap[$k])) {
                $iconurl = $this->image_url(
                    $iconmap[$k],
                    'coursereport_frictionradar'
                )->out(false);

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

        // Center.
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
            . $overall .
        '</text>';
        if (has_capability('moodle/course:update', $this->page->context)) {
            $refreshlabel = get_string('warmcache_now', 'coursereport_frictionradar');
            $refreshurl = new \moodle_url('/course/report/frictionradar/index.php', [
                'id' => $courseid,
                'warmcache' => 1,
                'sesskey' => sesskey(),
            ]);
            $ry = $cy + 68;
            $href = $refreshurl->out(false);
            $center .= '<a class="friction-refresh" href="' . $href . '" xlink:href="' . $href . '"'
                . ' aria-label="' . s($refreshlabel) . '" role="button" tabindex="0">'
                . '<title>' . s($refreshlabel) . '</title>'
                . '<circle cx="' . $cx . '" cy="' . $ry . '" r="14"'
                . ' fill="#F3F4F6" stroke="#CBD5E1" stroke-width="1" />'
                . '<path d="M ' . ($cx + 6) . ' ' . ($ry - 2) . ' A 7 7 0 1 1 ' . ($cx - 2) . ' ' . ($ry + 6) . '"'
                . ' fill="none" stroke="#374151" stroke-width="2"'
                . ' stroke-linecap="round" stroke-linejoin="round" />'
                . '<path d="M ' . ($cx + 6) . ' ' . ($ry - 2)
                . ' L ' . ($cx + 10) . ' ' . ($ry - 6)
                . ' M ' . ($cx + 6) . ' ' . ($ry - 2)
                . ' L ' . ($cx + 10) . ' ' . ($ry + 1) . '"'
                . ' fill="none" stroke="#374151" stroke-width="2"'
                . ' stroke-linecap="round" stroke-linejoin="round" />'
                . '</a>';
        }

        // Meta.
        $meta = '';
        if ($generated > 0) {
            $meta .= '<div class="small text-muted tool-friction-radar-created">'
                . s(get_string('generated_at', 'coursereport_frictionradar')) . ': ' . s(userdate($generated))
                . ' · '
                . s(get_string('window', 'coursereport_frictionradar')) . ': '
                . $window . ' ' . s(get_string('days', 'coursereport_frictionradar'))
                . '</div>';
        }

        // Legend.
        $legend  = '<div class="mt-4"><div class="row">';

        $labels = [];

        $uiscore   = get_string('ui_score', 'coursereport_frictionradar');
        $uiformula = get_string('ui_formula', 'coursereport_frictionradar');
        $uiinputs  = get_string('ui_inputs', 'coursereport_frictionradar');
        $uiparam   = get_string('ui_param', 'coursereport_frictionradar');
        $uivalue   = get_string('ui_value', 'coursereport_frictionradar');
        $uinotes   = get_string('ui_notes', 'coursereport_frictionradar');

        foreach ($order as $k) {
            $iconhtml = '';
            if (isset($iconmap[$k])) {
                $iconurl = $this->image_url(
                    $iconmap[$k],
                    'coursereport_frictionradar'
                );
                $iconhtml = html_writer::empty_tag('img', [
                    'src'   => $iconurl,
                    'alt'   => '',
                    'aria-hidden' => 'true',
                    'style' => 'width:48px; height:48px; margin-right:8px; opacity:0.85;',
                ]);
            }

            $score = (int)($segments[$k] ?? 0);

            $bd = $data['breakdown'][$k] ?? [];
            $formula = $bd['formula'] ?? '';
            $inputsjson = json_encode($bd['inputs'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $notes = $bd['notes'] ?? '';

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

            $labels[$k] = get_string('friction_' . $k, 'coursereport_frictionradar');

            $legend .= '<div class="col-12 col-md-6 col-lg-4 mb-2">'
                . '<div class="d-flex align-items-center">'
                . $iconhtml
                . '<span style="display:inline-block; width:16px; height:32px; border-radius:3px;'
                . 'background:' . $colors($score) . ';border:1px solid #E5E7EB; margin-right:10px;"></span>'
                . '<button type="button"
                    class="btn btn-link p-0 me-auto friction-info p-2 text-decoration-none"
                    data-friction="' . $k . '"
                    data-score="' . $score . '"
                    data-explanation="' . s($explanations[$k]) . '"
                    data-what="' . s(get_string('what_to_do', 'coursereport_frictionradar')) . '"
                    data-action="' . s($actions[$k]) . '"
                    data-formula="' . s($formula) . '"
                    data-inputs="' . s($inputsjson) . '"
                    data-notes="' . s($notes) . '"
                    data-ui-score="' . s($uiscore) . '"
                    data-ui-formula="' . s($uiformula) . '"
                    data-ui-inputs="' . s($uiinputs) . '"
                    data-ui-param="' . s($uiparam) . '"
                    data-ui-value="' . s($uivalue) . '"
                    data-ui-notes="' . s($uinotes) . '">'
                . s($labels[$k])
                . '</button>'
                . '<strong>' . $score . '</strong>'
                . '</div></div>';
        }
        $legend .= '</div></div>';

        // SVG wrapper (responsive).
        $svgtitle = s(get_string('friction_clock_aria', 'coursereport_frictionradar'));
        $svgtitleid = 'frictionradar-title-' . $courseid;
        $svg = '<svg style="max-width: 520px;" viewBox="0 0 400 500"
            width="100%"
            preserveAspectRatio="xMidYMid meet"
            xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink"
            role="img"
            aria-labelledby="' . s($svgtitleid) . '">'
            . '<title id="' . s($svgtitleid) . '">' . $svgtitle . '</title>'
            . $paths
            . $center
            . '</svg>';

        $this->page->requires->js_call_amd(
            'coursereport_frictionradar/friction_info',
            'init'
        );

        return '<div class="tool-frictionradar">'
            . '<div class="d-flex flex-column align-items-center">' . $svg . '</div>'
            . $meta
            . $legend
            . '</div>';
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
