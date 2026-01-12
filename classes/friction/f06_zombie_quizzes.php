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
 * F06 – Zombie Quizzes / Zombie-Quizze
 *
 * Detects quizzes that exist but are not used, abandoned, or only touched by very few learners.
 *
 * v1 metrics:
 * A = zombie ratio (quizzes with 0 attempts in window)                 0..1
 * B = abandonment ratio (1 - finished/total attempts in window)        0..1
 * C = low participation ratio (quizzes with < MIN_PARTICIPANTS)        0..1
 *
 * score = clamp( round( 100 * (0.45*A + 0.35*B + 0.20*C) ), 0, 100 )
 */
class f06_zombie_quizzes extends abstract_friction {

    private const MIN_PARTICIPANTS = 3;

    public function get_key(): string {
        return 'f06';
    }

    public function calculate(int $courseid, int $windowdays): array {
        $since = time() - ($windowdays * DAYSECS);

        $quizlist = $this->get_visible_quiz_cms($courseid);
        $totalquizzes = count($quizlist);

        if ($totalquizzes === 0) {
            return [
                'score' => 0,
                'breakdown' => [
                    'formula' =>
                        "score = clamp( round( 100 * (0.45*A + 0.35*B + 0.20*C) ), 0, 100 )\n\n" .
                        "A = zombie ratio (0 attempts)\n" .
                        "B = abandonment ratio (1 - finished/total)\n" .
                        "C = low participation ratio (< " . self::MIN_PARTICIPANTS . " learners)",
                    'inputs' => [
                        ['key' => 'quizzes_total', 'label' => 'Visible quizzes (total)', 'value' => 0],
                    ],
                    'notes' => $this->str('notes_f06', $windowdays),
                ],
            ];
        }

        $quizids = array_map(fn($q) => (int)$q['quizid'], $quizlist);

        // Attempts per quiz (student-like users) within window.
        $attemptstats = $this->attempt_stats_for_quizzes($courseid, $quizids, $since);

        $zombies = 0;
        $lowpart = 0;

        $attempttotal = 0;
        $attemptfinished = 0;

        foreach ($quizlist as $q) {
            $qid = (int)$q['quizid'];

            $a = $attemptstats[$qid] ?? [
                'attempts' => 0,
                'finished' => 0,
                'participants' => 0,
            ];

            $attempts = (int)$a['attempts'];
            $finished = (int)$a['finished'];
            $participants = (int)$a['participants'];

            $attempttotal += $attempts;
            $attemptfinished += $finished;

            if ($attempts === 0) {
                $zombies++;
            }

            if ($participants > 0 && $participants < self::MIN_PARTICIPANTS) {
                $lowpart++;
            }
        }

        // A: quizzes with 0 attempts.
        $A = min(1.0, max(0.0, $zombies / $totalquizzes));

        // B: abandonment ratio.
        // If there are zero attempts overall, keep B at 0 (A already captures the issue).
        if ($attempttotal > 0) {
            $finishrate = min(1.0, max(0.0, $attemptfinished / $attempttotal));
            $B = 1.0 - $finishrate;
        } else {
            $B = 0.0;
        }

        // C: low participation among quizzes that have at least one participant.
        // Normalize by total quizzes to keep it stable and simple.
        $C = min(1.0, max(0.0, $lowpart / $totalquizzes));

        $score = $this->clamp(
            (int)round(
                100 * (0.45 * $A + 0.35 * $B + 0.20 * $C)
            )
        );

        return [
            'score' => $score,
            'breakdown' => [
                'formula' =>
                    "score = clamp( round( 100 * (0.45*A + 0.35*B + 0.20*C) ), 0, 100 )\n\n" .
                    "A = zombie ratio (quizzes with 0 attempts in window)\n" .
                    "B = abandonment ratio (1 - finished/total attempts in window)\n" .
                    "C = low participation ratio (quizzes with < " . self::MIN_PARTICIPANTS . " learners in window)",
                'inputs' => [
                    ['key' => 'quizzes_total', 'label' => 'Visible quizzes (total)', 'value' => $totalquizzes],
                    ['key' => 'zombies', 'label' => 'Quizzes with 0 attempts (window)', 'value' => $zombies],
                    ['key' => 'attempts_total', 'label' => 'Attempts (window, students)', 'value' => $attempttotal],
                    ['key' => 'attempts_finished', 'label' => 'Finished attempts (window)', 'value' => $attemptfinished],
                    ['key' => 'low_participation_quizzes', 'label' => 'Quizzes with low participation', 'value' => $lowpart],
                    ['key' => 'A', 'label' => 'Zombie ratio (0..1)', 'value' => round($A, 3)],
                    ['key' => 'B', 'label' => 'Abandonment ratio (0..1)', 'value' => round($B, 3)],
                    ['key' => 'C', 'label' => 'Low participation ratio (0..1)', 'value' => round($C, 3)],
                ],
                'notes' => $this->str('notes_f06', $windowdays),
            ],
        ];
    }

    /**
     * Fetch visible quiz course modules (quizid + cmid).
     */
    private function get_visible_quiz_cms(int $courseid): array {
        global $DB;

        $sql = "SELECT q.id AS quizid, cm.id AS cmid
                  FROM {quiz} q
                  JOIN {course_modules} cm
                    ON cm.instance = q.id
                  JOIN {modules} m
                    ON m.id = cm.module
                 WHERE q.course = :courseid
                   AND m.name = 'quiz'
                   AND cm.visible = 1
                   AND cm.deletioninprogress = 0";

        $rows = $DB->get_records_sql($sql, ['courseid' => $courseid]);
        $out = [];
        foreach ($rows as $r) {
            $out[] = ['quizid' => (int)$r->quizid, 'cmid' => (int)$r->cmid];
        }
        return $out;
    }

    /**
     * Attempt stats per quiz within window for student-like users.
     *
     * Returns map:
     *  quizid => ['attempts'=>int, 'finished'=>int, 'participants'=>int]
     *
     * Student filtering:
     * - course context role assignments with archetype=student or shortname=student
     */
    private function attempt_stats_for_quizzes(int $courseid, array $quizids, int $since): array {
        global $DB;

        if (empty($quizids)) {
            return [];
        }

        list($insql, $params) = $DB->get_in_or_equal($quizids, SQL_PARAMS_NAMED, 'qid');

        $sql = "SELECT
                    qa.quiz,
                    COUNT(qa.id) AS attempts,
                    SUM(CASE WHEN qa.state = 'finished' THEN 1 ELSE 0 END) AS finished,
                    COUNT(DISTINCT qa.userid) AS participants
                FROM {quiz_attempts} qa
                JOIN {context} ctx
                  ON ctx.contextlevel = 50
                 AND ctx.instanceid = :courseid
                JOIN {role_assignments} ra
                  ON ra.contextid = ctx.id
                 AND ra.userid = qa.userid
                JOIN {role} r
                  ON r.id = ra.roleid
                WHERE qa.quiz $insql
                  AND qa.timemodified >= :since
                  AND qa.userid > 0
                  AND (r.archetype = 'student' OR r.shortname = 'student')
                GROUP BY qa.quiz";

        $params['courseid'] = $courseid;
        $params['since'] = $since;

        $rows = $DB->get_records_sql($sql, $params);

        $out = [];
        foreach ($rows as $r) {
            $qid = (int)$r->quiz;
            $out[$qid] = [
                'attempts' => (int)$r->attempts,
                'finished' => (int)$r->finished,
                'participants' => (int)$r->participants,
            ];
        }

        return $out;
    }
}
