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

namespace coursereport_frictionradar\friction;


/**
 * F02 – Didactic Bottlenecks / Didaktische Engstellen
 *
 * v1 metrics (robust, generic):
 * A = activity transition density (how often activity types change across the course sequence)
 * B = support gap ratio (demanding items without nearby explanatory resources)
 * C = retry & delay signal (placeholder for now; can be replaced with log/attempt metrics later)
 */
class f02_didactic_bottlenecks extends abstract_friction
{
    /**
     * Return the friction key.
     *
     * @return string
     */
    public function get_key(): string {
        return 'f02';
    }

    /**
     * Calculate score and breakdown for a course.
     *
     * @param int $courseid Course id.
     * @param int $windowdays Window size in days.
     * @return array Calculation result.
     */
    public function calculate(int $courseid, int $windowdays): array {
        // A: transitions between module types across the course sequence.
        $a = $this->activity_transition_density($courseid); // 0..1

        // B: demanding items without nearby resource/label/page "support".
        $b = $this->support_gap_ratio($courseid); // 0..1

        // C: retry & delay signal (v1 placeholder, later use logstore/attempt tables).
        $c = $this->retry_delay_signal($courseid, $windowdays); // 0..1

        $score = $this->clamp(
            (int)round(
                100 * (0.4 * $a + 0.35 * $b + 0.25 * $c)
            )
        );

        return [
            'score' => $score,
            'breakdown' => [
                'formula' => $this->str('formula_f02'),
                'inputs' => [
                    ['key' => 'A', 'label' => $this->str('input_f02_a'), 'value' => round($a, 3)],
                    ['key' => 'B', 'label' => $this->str('input_f02_b'), 'value' => round($b, 3)],
                    ['key' => 'C', 'label' => $this->str('input_f02_c'), 'value' => round($c, 3)],
                ],
                'notes' => $this->str('notes_f02', $windowdays),
            ],
        ];
    }

    /**
     * A – Activity transition density (0..1).
     *
     * We build a sequence of visible course modules ordered by section then by cm.id,
     * then count transitions where the module type changes (e.g. forum -> quiz).
     *
     * A = transitions / max(1, (n-1))
     */
    private function activity_transition_density(int $courseid): float {
        global $DB;

        $sql = "SELECT cm.id, m.name AS modname
                  FROM {course_modules} cm
                  JOIN {modules} m ON m.id = cm.module
                  JOIN {course_sections} cs ON cs.id = cm.section
                 WHERE cm.course = :courseid
                   AND cm.visible = 1
                   AND cm.deletioninprogress = 0
              ORDER BY cs.section ASC, cm.id ASC";

        $rows = $DB->get_records_sql($sql, ['courseid' => $courseid]);
        if (!$rows) {
            return 0.0;
        }

        $mods = [];
        foreach ($rows as $r) {
            $mods[] = (string)$r->modname;
        }

        $n = count($mods);
        if ($n <= 1) {
            return 0.0;
        }

        $transitions = 0;
        for ($i = 1; $i < $n; $i++) {
            if ($mods[$i] !== $mods[$i - 1]) {
                $transitions++;
            }
        }

        return min(1.0, max(0.0, $transitions / ($n - 1)));
    }

    /**
     * B – Support gap ratio (0..1).
     *
     * "Demanding" modules (generic approximation):
     * - quiz, assign, workshop, lesson, scorm, lti, data, glossary, feedback
     *
     * "Support" modules:
     * - label, page, resource, book, url
     *
     * We count demanding items that have NO support item within +/- 2 items in the
     * ordered course sequence.
     *
     * B = unsupported_demanding / max(1, demanding_count)
     */
    private function support_gap_ratio(int $courseid): float {
        global $DB;

        $sql = "SELECT cm.id, m.name AS modname
                  FROM {course_modules} cm
                  JOIN {modules} m ON m.id = cm.module
                  JOIN {course_sections} cs ON cs.id = cm.section
                 WHERE cm.course = :courseid
                   AND cm.visible = 1
                   AND cm.deletioninprogress = 0
              ORDER BY cs.section ASC, cm.id ASC";

        $rows = $DB->get_records_sql($sql, ['courseid' => $courseid]);
        if (!$rows) {
            return 0.0;
        }

        $seq = [];
        foreach ($rows as $r) {
            $seq[] = (string)$r->modname;
        }

        $demanding = [
            'quiz' => true,
            'assign' => true,
            'workshop' => true,
            'lesson' => true,
            'scorm' => true,
            'lti' => true,
            'data' => true,
            'glossary' => true,
            'feedback' => true,
        ];

        $support = [
            'label' => true,
            'page' => true,
            'resource' => true,
            'book' => true,
            'url' => true,
        ];

        $demandingcount = 0;
        $unsupported = 0;

        $n = count($seq);
        for ($i = 0; $i < $n; $i++) {
            $mod = $seq[$i];
            if (!isset($demanding[$mod])) {
                continue;
            }

            $demandingcount++;

            // Look around within +/-2 items for support.
            $hassupport = false;
            $from = max(0, $i - 2);
            $to   = min($n - 1, $i + 2);

            for ($j = $from; $j <= $to; $j++) {
                if ($j === $i) {
                    continue;
                }
                if (isset($support[$seq[$j]])) {
                    $hassupport = true;
                    break;
                }
            }

            if (!$hassupport) {
                $unsupported++;
            }
        }

        if ($demandingcount <= 0) {
            return 0.0;
        }

        return min(1.0, max(0.0, $unsupported / $demandingcount));
    }

    /**
     * C – Retry & delay signal (0..1).
     *
     * v1 placeholder:
     * - returns 0.0 so we don't pretend we have attempt/delay evidence yet.
     *
     * v2 idea:
     * - use logstore_standard_log + mod attempt tables to derive:
     *   - unusual retries (quiz attempts, assignment resubmits)
     *   - time-to-complete vs typical
     */
    private function retry_delay_signal(int $courseid, int $windowdays): float {
        return 0.0;
    }
}
