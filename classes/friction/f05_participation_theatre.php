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
namespace tool_frictionradar\friction;

defined('MOODLE_INTERNAL') || die();

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
class f05_participation_theatre extends abstract_friction {

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

    public function get_key(): string {
        return 'f05';
    }

    public function calculate(int $courseid, int $windowdays): array {
        $since = time() - ($windowdays * DAYSECS);

        $stats = $this->student_log_stats($courseid, $since);

        $viewers = (int)$stats['viewers'];
        $passive = (int)$stats['passive'];
        $engaged = (int)$stats['engaged'];
        $substantiveTotal = (int)$stats['substantive_total'];

        // A: passive viewer ratio.
        $A = ($viewers > 0) ? ($passive / $viewers) : 0.0;
        $A = min(1.0, max(0.0, $A));

        // B: interaction depth (inverse).
        // avg substantive per viewer, normalized by /5 (>=5 is "good enough"), then inverted.
        $avgSub = ($viewers > 0) ? ($substantiveTotal / $viewers) : 0.0;
        $depthNorm = min(1.0, max(0.0, $avgSub / 5.0));
        $B = 1.0 - $depthNorm;

        // C: engagement gap (viewers minus engaged).
        $C = ($viewers > 0) ? (1.0 - min(1.0, max(0.0, $engaged / $viewers))) : 0.0;

        $score = $this->clamp(
            (int)round(
                100 * (0.5 * $A + 0.3 * $B + 0.2 * $C)
            )
        );

        return [
            'score' => $score,
            'breakdown' => [
                'formula' =>
                    "score = clamp( round( 100 * (0.5*A + 0.3*B + 0.2*C) ), 0, 100 )\n\n" .
                    "A = passive viewer ratio\n" .
                    "B = low interaction depth (inverse of avg substantive actions)\n" .
                    "C = engagement gap (viewers vs engaged)",
                'inputs' => [
                    ['key' => 'min_views', 'label' => 'Viewer threshold (min views)', 'value' => self::MIN_VIEWS],
                    ['key' => 'viewers', 'label' => 'Viewers (>= min views)', 'value' => $viewers],
                    ['key' => 'passive', 'label' => 'Passive viewers (0 substantive)', 'value' => $passive],
                    ['key' => 'engaged', 'label' => 'Engaged users (>=1 substantive)', 'value' => $engaged],
                    ['key' => 'substantive_total', 'label' => 'Total substantive actions', 'value' => $substantiveTotal],
                    ['key' => 'avg_sub', 'label' => 'Avg substantive per viewer', 'value' => round($avgSub, 2)],
                    ['key' => 'A', 'label' => 'Passive viewer ratio (0..1)', 'value' => round($A, 3)],
                    ['key' => 'B', 'label' => 'Low interaction depth (0..1)', 'value' => round($B, 3)],
                    ['key' => 'C', 'label' => 'Engagement gap (0..1)', 'value' => round($C, 3)],
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

        // Prepare IN (...) for substantive actions.
        list($actionsql, $actionparams) = $DB->get_in_or_equal(
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
        $substantiveTotal = 0;

        foreach ($rows as $r) {
            $views = (int)($r->views ?? 0);
            $sub = (int)($r->substantive ?? 0);

            if ($views < self::MIN_VIEWS) {
                continue;
            }

            $viewers++;
            $substantiveTotal += $sub;

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
            'substantive_total' => $substantiveTotal,
        ];
    }
}
