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

namespace coursereport_frictionradar\service;

use coursereport_frictionradar\friction\friction_interface;
use coursereport_frictionradar\friction\f01_cognitive_overload;
use coursereport_frictionradar\friction\f02_didactic_bottlenecks;
use coursereport_frictionradar\friction\f03_navigation_chaos;
use coursereport_frictionradar\friction\f04_overambitious_entry;
use coursereport_frictionradar\friction\f05_participation_theatre;
use coursereport_frictionradar\friction\f06_zombie_quizzes;
use coursereport_frictionradar\friction\f07_unclear_expectations;
use coursereport_frictionradar\friction\f08_structure_paradox;
use coursereport_frictionradar\friction\f09_resource_overload;
use coursereport_frictionradar\friction\f10_hidden_dependencies;
use coursereport_frictionradar\friction\f11_frust_scroll;
use coursereport_frictionradar\friction\f12_deadline_panic;


/**
 * Calculates course friction scores.
 *
 * @package    coursereport_frictionradar
 */
class friction_calculator
{
    /**
     * Ordered list of friction segment keys.
     */
    private const ORDER = [
        'f01',
        'f02',
        'f03',
        'f04',
        'f05',
        'f06',
        'f07',
        'f08',
        'f09',
        'f10',
        'f11',
        'f12',
    ];

    /**
     * Calculate scores for a course.
     *
     * @param int $courseid Course id.
     * @param int $windowdays Window size in days.
     * @return array Score payload.
     */
    public static function calculate_course(int $courseid, int $windowdays = 42): array {
        return self::calculate_for_course($courseid, $windowdays);
    }

    /**
     * Calculate scores for a course.
     *
     * @param int $courseid Course id.
     * @param int $windowdays Window size in days.
     * @return array Score payload.
     */
    public static function calculate_for_course(int $courseid, int $windowdays = 42): array {
        $frictions = self::get_frictions();

        $segments = array_fill_keys(self::ORDER, 0);
        $breakdown = [];

        foreach ($frictions as $friction) {
            $key = $friction->get_key();
            $result = $friction->calculate($courseid, $windowdays);

            $segments[$key] = (int)($result['score'] ?? 0);
            $breakdown[$key] = (array)($result['breakdown'] ?? []);
        }

        return [
            'overall'      => self::overall_score($segments),
            'segments'     => $segments,
            'breakdown'    => $breakdown,
            'generated_at' => time(),
            'window_days'  => $windowdays,
        ];
    }

    /**
     * Get friction calculators in display order.
     *
     * @return friction_interface[]
     */
    private static function get_frictions(): array {
        return [
            new f01_cognitive_overload(),
            new f02_didactic_bottlenecks(),
            new f03_navigation_chaos(),
            new f04_overambitious_entry(),
            new f05_participation_theatre(),
            new f06_zombie_quizzes(),
            new f07_unclear_expectations(),
            new f08_structure_paradox(),
            new f09_resource_overload(),
            new f10_hidden_dependencies(),
            new f11_frust_scroll(),
            new f12_deadline_panic(),
        ];
    }

    /**
     * Calculate the overall score from segment scores.
     *
     * @param array $segments Segment scores.
     * @return int Overall score.
     */
    private static function overall_score(array $segments): int {
        if (empty($segments)) {
            return 0;
        }
        return (int)round(array_sum($segments) / count($segments));
    }
}
