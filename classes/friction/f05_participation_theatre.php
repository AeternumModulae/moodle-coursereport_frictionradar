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
 * F05 – Participation Theatre / Passive Anwesenheit
 *
 * Goal: detect "being present" (views) without meaningful engagement.
 *
 * v1 metrics (uses logstore_standard_log, robust enough):
 * A = passive viewer ratio (users with >=N views but 0 substantive actions)
 * B = low interaction depth (inverse of avg substantive actions per viewer)
 * C = engagement gap (viewers vs. engaged users)
 *
 * score = clamp( round( 100 * (0.5*A + 0.3*B + 0.2*C) ), 0, 100 )
 */
class f05_participation_theatre extends abstract_friction
{
    /** Minimum views in window to count a user as "present". */
    private const MIN_VIEWS = 5;

    /** Substantive actions (beyond plain viewing). */
    private const SUBSTANTIVE_ACTIONS = [
        'created',
        'submitted',
        'updated',
        'deleted',
        'commented',
        'posted',
        'sent',
        'uploaded',
        'answered',
        'attempted',
    ];

    /**
     * Return the friction key.
     *
     * @return string
     */
    public function get_key(): string {
        return 'f05';
    }

    /**
     * Calculate score and breakdown for a course.
     *
     * @param int $courseid Course id.
     * @param int $windowdays Window size in days.
     * @return array Calculation result.
     */
    public function calculate(int $courseid, int $windowdays): array {
        $since = time() - ($windowdays * DAYSECS);

        $stats = $this->student_log_stats($courseid, $since);

        $viewers = (int)$stats['viewers'];
        $passive = (int)$stats['passive'];
        $engaged = (int)$stats['engaged'];
        $substantivetotal = (int)$stats['substantive_total'];

        // A: passive viewer ratio.
        $a = ($viewers > 0) ? ($passive / $viewers) : 0.0;
        $a = min(1.0, max(0.0, $a));

        // B: interaction depth (inverse).
        // Avg substantive per viewer, normalized by /5 (>=5 is "good enough"), then inverted.
        $avgsub = ($viewers > 0) ? ($substantivetotal / $viewers) : 0.0;
        $depthnorm = min(1.0, max(0.0, $avgsub / 5.0));
        $b = 1.0 - $depthnorm;

        // C: engagement gap (viewers minus engaged).
        $c = ($viewers > 0) ? (1.0 - min(1.0, max(0.0, $engaged / $viewers))) : 0.0;

        $score = $this->clamp(
            (int)round(
                100 * (0.5 * $a + 0.3 * $b + 0.2 * $c)
            )
        );

        return [
            'score' => $score,
            'breakdown' => [
                'formula' => $this->str('formula_f05'),
                'inputs' => [
                    ['key' => 'min_views', 'label' => $this->str('input_f05_min_views'), 'value' => self::MIN_VIEWS],
                    ['key' => 'viewers', 'label' => $this->str('input_f05_viewers'), 'value' => $viewers],
                    ['key' => 'passive', 'label' => $this->str('input_f05_passive'), 'value' => $passive],
                    ['key' => 'engaged', 'label' => $this->str('input_f05_engaged'), 'value' => $engaged],
                    [
                        'key' => 'substantive_total',
                        'label' => $this->str('input_f05_substantive_total'),
                        'value' => $substantivetotal,
                    ],
                    ['key' => 'avg_sub', 'label' => $this->str('input_f05_avg_sub'), 'value' => round($avgsub, 2)],
                    ['key' => 'A', 'label' => $this->str('input_f05_a'), 'value' => round($a, 3)],
                    ['key' => 'B', 'label' => $this->str('input_f05_b'), 'value' => round($b, 3)],
                    ['key' => 'C', 'label' => $this->str('input_f05_c'), 'value' => round($c, 3)],
                ],
                'notes' => $this->str('notes_f05', $windowdays),
            ],
        ];
    }

    /**
     * Aggregate stats for "student-like" users using logstore_standard_log.
     *
     * Returns:
     * - viewers: users with views >= MIN_VIEWS
     * - passive: viewers with substantive = 0
     * - engaged: viewers with substantive >= 1
     * - substantive_total: sum(substantive) across viewers
     *
     * Notes:
     * - Filters to roles with archetype 'student' OR shortname 'student' in this course context.
     * - If your institution uses custom student roles without archetype/shortname, we can extend this later.
     */
    private function student_log_stats(int $courseid, int $since): array {
        global $DB;

        if (!$DB->get_manager()->table_exists('logstore_standard_log')) {
            return [
                'viewers' => 0,
                'passive' => 0,
                'engaged' => 0,
                'substantive_total' => 0,
            ];
        }

        // Prepare IN (...) for substantive actions.
        [$actionsql, $actionparams] = $DB->get_in_or_equal(
            self::SUBSTANTIVE_ACTIONS,
            SQL_PARAMS_NAMED,
            'act'
        );

        // Get per-user counts.
        $sql = "SELECT
                    l.userid,
                    SUM(CASE WHEN l.action = 'viewed' THEN 1 ELSE 0 END) AS views,
                    SUM(CASE WHEN l.action $actionsql THEN 1 ELSE 0 END) AS substantive
                FROM {logstore_standard_log} l
                JOIN {context} ctx
                  ON ctx.contextlevel = 50
                 AND ctx.instanceid = l.courseid
                JOIN {role_assignments} ra
                  ON ra.contextid = ctx.id
                 AND ra.userid = l.userid
                JOIN {role} r
                  ON r.id = ra.roleid
                WHERE l.courseid = :courseid
                  AND l.timecreated >= :since
                  AND l.userid > 0
                  AND (r.archetype = 'student' OR r.shortname = 'student')
                GROUP BY l.userid";

        $params = array_merge(
            [
                'courseid' => $courseid,
                'since' => $since,
            ],
            $actionparams
        );

        $rows = $DB->get_records_sql($sql, $params);

        $viewers = 0;
        $passive = 0;
        $engaged = 0;
        $substantivetotal = 0;

        foreach ($rows as $r) {
            $views = (int)($r->views ?? 0);
            $sub = (int)($r->substantive ?? 0);

            if ($views < self::MIN_VIEWS) {
                continue;
            }

            $viewers++;
            $substantivetotal += $sub;

            if ($sub <= 0) {
                $passive++;
            } else {
                $engaged++;
            }
        }

        return [
            'viewers' => $viewers,
            'passive' => $passive,
            'engaged' => $engaged,
            'substantive_total' => $substantivetotal,
        ];
    }
}
