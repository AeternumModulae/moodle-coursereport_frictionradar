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

defined('MOODLE_INTERNAL') || die();

class friction_calculator {

    private const ORDER = ['f01','f02','f03','f04','f05','f06','f07','f08','f09','f10','f11','f12'];

    public static function calculate_course(int $courseid, int $windowdays = 42): array {
        return self::calculate_for_course($courseid, $windowdays);
    }

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

    private static function overall_score(array $segments): int {
        if (empty($segments)) {
            return 0;
        }
        return (int)round(array_sum($segments) / count($segments));
    }
}
