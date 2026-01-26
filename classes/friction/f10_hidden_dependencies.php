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
 * F10 – Hidden Dependencies / Versteckte Voraussetzungen
 *
 * Measures how often access restrictions create implicit prerequisites.
 */
class f10_hidden_dependencies extends abstract_friction
{
    /**
     * Return the friction key.
     *
     * @return string
     */
    public function get_key(): string {
        return 'f10';
    }

    /**
     * Calculate score and breakdown for a course.
     *
     * @param int $courseid Course id.
     * @param int $windowdays Window size in days.
     * @return array Calculation result.
     */
    public function calculate(int $courseid, int $windowdays): array {
        global $DB;

        $cms = $DB->get_records_sql(
            "SELECT id, availability
               FROM {course_modules}
              WHERE course = :courseid
                AND visible = 1
                AND deletioninprogress = 0",
            ['courseid' => $courseid]
        );

        if (!$cms) {
            return $this->empty_result($windowdays);
        }

        $total = 0;
        $restricted = 0;
        $noinfo = 0;
        $chains = 0;

        foreach ($cms as $cm) {
            $total++;

            if (empty($cm->availability)) {
                continue;
            }

            $restricted++;

            $availability = json_decode($cm->availability, true);
            if (!is_array($availability)) {
                continue;
            }

            $conditions = $availability['c'] ?? [];

            // B – No visible explanation text.
            if (empty($availability['showc'])) {
                $noinfo++;
            }

            // C – Multiple dependency conditions.
            if (count($conditions) > 1) {
                $chains++;
            }
        }

        $a = $restricted / max(1, $total);
        $b = $noinfo / max(1, $restricted);
        $c = $chains / max(1, $restricted);

        $score = $this->clamp(
            (int)round(
                100 * (0.4 * $a + 0.35 * $b + 0.25 * $c)
            )
        );

        return [
            'score' => $score,
            'breakdown' => [
                'formula' => $this->str('formula_f10'),
                'inputs' => [
                    ['key' => 'total', 'label' => $this->str('input_f10_total'), 'value' => $total],
                    ['key' => 'restricted', 'label' => $this->str('input_f10_restricted'), 'value' => $restricted],
                    ['key' => 'A', 'label' => $this->str('input_f10_a'), 'value' => round($a, 3)],
                    ['key' => 'B', 'label' => $this->str('input_f10_b'), 'value' => round($b, 3)],
                    ['key' => 'C', 'label' => $this->str('input_f10_c'), 'value' => round($c, 3)],
                ],
                'notes' => $this->str('notes_f10', $windowdays),
            ],
        ];
    }

    /**
     * Build the empty-result payload when no restrictions exist.
     *
     * @param int $windowdays Window size in days.
     * @return array Calculation result.
     */
    private function empty_result(int $windowdays): array {
        return [
            'score' => 0,
            'breakdown' => [
                'formula' => $this->str('formula_f10_empty'),
                'inputs' => [],
                'notes' => $this->str('notes_f10', $windowdays),
            ],
        ];
    }
}
