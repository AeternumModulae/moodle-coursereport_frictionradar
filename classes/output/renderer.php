<?php
/**
 * Copyright (c) 2026 Jan Svoboda <jan.svoboda@bittra.de>
 * Project: Aeternum Modulae – https://aeternummodulae.com
 *
 * This file is part of the Aeternum Modulae Moodle plugin "Friction Radar".
 *
 * Licensed under the GNU General Public License v3.0 or later.
 * https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace coursereport_frictionradar\output;

use html_writer;

defined('MOODLE_INTERNAL') || die();

class renderer extends \plugin_renderer_base {

    public function friction_clock(array $data, int $courseid): string {
        $segments  = $data['segments'] ?? [];
        $overall   = (int)($data['overall'] ?? 0);
        $generated = (int)($data['generated_at'] ?? 0);
        $window    = (int)($data['window_days'] ?? 42);

        // Color palette
        $colors = function(int $score): string {
            if ($score <= 15) return '#E6EEF6'; // Mist Blue
            if ($score <= 30) return '#C9DBEE'; // Ice Blue
            if ($score <= 50) return '#9FBAD6'; // Steel Blue Light
            if ($score <= 70) return '#6F8FB3'; // Slate Blue
            if ($score <= 85) return '#3F658F'; // Deep Steel Blue
            return '#1F3A5F';                  // Midnight Blue
        };

        $order = ['f01','f02','f03','f04','f05','f06','f07','f08','f09','f10','f11','f12'];

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
        $rOuter = 200;
        $rInner = 120;
        $startAngle = -90;
        $delta = 360 / 12;

        $paths = '';

        for ($i = 0; $i < 12; $i++) {
            $k = $order[$i];
            $score = (int)($segments[$k] ?? 0);

            $a1 = deg2rad($startAngle + $i * $delta);
            $a2 = deg2rad($startAngle + ($i + 1) * $delta);

            $p = $this->donut_segment_path($cx, $cy, $rOuter, $rInner, $a1, $a2);
            $fill = $colors($score);

            // Segment.
            $paths .= '<path d="'.$p.'" fill="'.$fill.'" stroke="#E5E7EB" stroke-width="1"/>';

            // Icon.
            if (isset($iconmap[$k])) {
                $iconurl = $this->image_url(
                    $iconmap[$k],
                    'coursereport_frictionradar'
                )->out(false);

                $mid = deg2rad($startAngle + ($i + 0.5) * $delta);
                $rIcon = ($rOuter + $rInner) / 2;

                $ix = $cx + cos($mid) * $rIcon;
                $iy = $cy + sin($mid) * $rIcon;

                $iconsize = 48;

                $paths .= '<image href="'.$iconurl.'"'
                    . ' x="'.($ix - $iconsize / 2).'"'
                    . ' y="'.($iy - $iconsize / 2).'"'
                    . ' width="'.$iconsize.'"'
                    . ' height="'.$iconsize.'"'
                    . ' preserveAspectRatio="xMidYMid meet"'
                    . ' />';
            }
        }

        // Center.
        $center  = '<circle cx="'.$cx.'" cy="'.$cy.'" r="95" fill="#FFFFFF" stroke="#E5E7EB" stroke-width="2" />';
        $center .= '<text x="'.$cx.'" y="'.($cy - 18).'" text-anchor="middle"
            font-family="system-ui, -apple-system, Segoe UI, Roboto, Arial"
            font-size="14" fill="#6B7280">'
            . s(get_string('overall_score', 'coursereport_frictionradar')) .
        '</text>';
        $center .= '<text x="'.$cx.'" y="'.($cy + 28).'" text-anchor="middle"
            font-family="system-ui, -apple-system, Segoe UI, Roboto, Arial"
            font-size="56" font-weight="700" fill="#111827">'
            . $overall .
        '</text>';

        // Meta.
        $meta = '';
        if ($generated > 0) {
            $meta .= '<div class="small text-muted tool-friction-radar-created">'
                . s(get_string('generated_at','coursereport_frictionradar')) . ': ' . userdate($generated)
                . ' · '
                . s(get_string('window','coursereport_frictionradar')) . ': '
                . $window . ' ' . s(get_string('days','coursereport_frictionradar'))
                . '</div>';
        }

        // Legend.
        $legend  = '<div class="mt-4"><div class="row">';

        $labels = [];

        $ui_score   = get_string('ui_score', 'coursereport_frictionradar');
        $ui_formula = get_string('ui_formula', 'coursereport_frictionradar');
        $ui_inputs  = get_string('ui_inputs', 'coursereport_frictionradar');
        $ui_param   = get_string('ui_param', 'coursereport_frictionradar');
        $ui_value   = get_string('ui_value', 'coursereport_frictionradar');
        $ui_notes   = get_string('ui_notes', 'coursereport_frictionradar');

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
                . 'background:'.$colors($score).';border:1px solid #E5E7EB; margin-right:10px;"></span>'
                . '<button type="button"
                    class="btn btn-link p-0 me-auto friction-info p-2 text-decoration-none"
                    data-friction="'.$k.'"
                    data-score="'.$score.'"
                    data-explanation="'.s($explanations[$k]).'"
                    data-what="'.s(get_string('what_to_do', 'coursereport_frictionradar')).'"
                    data-action="'.s($actions[$k]).'"
                    data-formula="'.s($formula).'"
                    data-inputs="'.s($inputsjson).'"
                    data-notes="'.s($notes).'"
                    data-ui-score="'.s($ui_score).'"
                    data-ui-formula="'.s($ui_formula).'"
                    data-ui-inputs="'.s($ui_inputs).'"
                    data-ui-param="'.s($ui_param).'"
                    data-ui-value="'.s($ui_value).'"
                    data-ui-notes="'.s($ui_notes).'">'
                . $labels[$k]
                . '</button>'
                . '<strong>'.$score.'</strong>'
                . '</div></div>';
        }
        $legend .= '</div></div>';

        // SVG wrapper (responsive).
        $svg = '<svg style="max-width: 520px;" viewBox="0 0 400 500"
            width="100%"
            preserveAspectRatio="xMidYMid meet"
            xmlns="http://www.w3.org/2000/svg"
            role="img"
            aria-label="Friction Clock">'
            . $paths
            . $center
            . '</svg>';

        $this->page->requires->js_call_amd(
            'coursereport_frictionradar/friction_info',
            'init'
        );

        return '<div class="tool-frictionradar">'
            . '<div class="d-flex flex-column align-items-center">'.$svg.'</div>'
            . $meta
            . $legend
            . '</div>';
    }

    private function donut_segment_path(
        float $cx,
        float $cy,
        float $rOuter,
        float $rInner,
        float $a1,
        float $a2
    ): string {
        $largeArc = (($a2 - $a1) > M_PI) ? 1 : 0;

        $x1 = $cx + $rOuter * cos($a1);
        $y1 = $cy + $rOuter * sin($a1);
        $x2 = $cx + $rOuter * cos($a2);
        $y2 = $cy + $rOuter * sin($a2);

        $x3 = $cx + $rInner * cos($a2);
        $y3 = $cy + $rInner * sin($a2);
        $x4 = $cx + $rInner * cos($a1);
        $y4 = $cy + $rInner * sin($a1);

        return sprintf(
            'M %.3f %.3f A %.3f %.3f 0 %d 1 %.3f %.3f
             L %.3f %.3f
             A %.3f %.3f 0 %d 0 %.3f %.3f Z',
            $x1, $y1,
            $rOuter, $rOuter,
            $largeArc,
            $x2, $y2,
            $x3, $y3,
            $rInner, $rInner,
            $largeArc,
            $x4, $y4
        );
    }
}
