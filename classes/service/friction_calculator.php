<?php
namespace tool_frictionradar\service;

defined('MOODLE_INTERNAL') || die();

class friction_calculator {

    public static function calculate_for_course(int $courseid, int $windowdays = 42): array {
        global $DB;

        $since = utils::ts_minus_days($windowdays);
        $lr = new log_reader($DB);

        $moduleviews = $lr->get_module_views($courseid, $since);
        $courseviews = $lr->get_course_views($courseid, $since);
        $courseevents = $lr->get_course_events($courseid, $since);

        // Group module views by user.
        $viewsbyuser = [];
        foreach ($moduleviews as $row) {
            $uid = (int)$row->userid;
            $viewsbyuser[$uid][] = ['cmid' => (int)$row->cmid, 't' => (int)$row->timecreated];
        }

        // Group course views by user.
        $courseviewsbyuser = [];
        foreach ($courseviews as $row) {
            $uid = (int)$row->userid;
            $courseviewsbyuser[$uid][] = (int)$row->timecreated;
        }

        // Course events by user for sessions.
        $eventsbyuser = [];
        foreach ($courseevents as $row) {
            $uid = (int)$row->userid;
            $eventsbyuser[$uid][] = (int)$row->timecreated;
        }

        // Enrollment list for inactive ratio.
        $enrolledusers = self::get_enrolled_userids($courseid);

        // Derived populations.
        $rr_pop = [];
        $bf_pop = [];
        $jc_pop = [];
        $cor_pop = [];
        $lar_pop = [];
        $rsv_pop = [];
        $sdr_pop = [];
        $w1s_pop = [];
        $edr_flags = [];

        // For dwell-time blocks (SDR proxy).
        foreach ($viewsbyuser as $uid => $seq) {
            // Ensure sorted.
            usort($seq, fn($a, $b) => $a['t'] <=> $b['t']);

            // RR.
            $counts = [];
            foreach ($seq as $e) {
                $counts[$e['cmid']] = ($counts[$e['cmid']] ?? 0) + 1;
            }
            $distinct = count($counts);
            if ($distinct > 0) {
                $revisited = 0;
                foreach ($counts as $c) {
                    if ($c >= 2) $revisited++;
                }
                $rr_pop[] = $revisited / $distinct;
            }

            // BF / JC / RSV.
            $cmseq = array_map(fn($e) => $e['cmid'], $seq);
            $tseq  = array_map(fn($e) => $e['t'], $seq);
            $n = count($cmseq);

            if ($n >= 3) {
                $back = 0;
                for ($i = 2; $i < $n; $i++) {
                    if ($cmseq[$i] === $cmseq[$i-2] && $cmseq[$i] !== $cmseq[$i-1]) {
                        $back++;
                    }
                }
                $bf_pop[] = $back / max(1, ($n - 2));
            }

            if ($n >= 2) {
                $pairs = [];
                $jumps = 0;
                $rapid = 0;
                for ($i = 1; $i < $n; $i++) {
                    if ($cmseq[$i] !== $cmseq[$i-1]) {
                        $jumps++;
                        $pairs[$cmseq[$i-1] . '>' . $cmseq[$i]] = 1;
                        if (($tseq[$i] - $tseq[$i-1]) < 10) {
                            $rapid++;
                        }
                    }
                }
                $jc_pop[] = count($pairs); // raw unique jump count
                $rsv_pop[] = $rapid / max(1, $jumps);
            }

            // COR and LAR.
            $courseviewsn = isset($courseviewsbyuser[$uid]) ? count($courseviewsbyuser[$uid]) : 0;
            $distinctmodules = count($counts);
            if ($distinctmodules > 0) {
                $cor_pop[] = $courseviewsn / $distinctmodules;
            }

            // LAR: course views vs module interactions.
            $moduleactions = $n;
            $lar_pop[] = $courseviewsn / max(1, $moduleactions);

            // SDR: dwell blocks per module view sequence.
            $short = 0;
            $blocks = 0;
            $blockstart = 0;
            $blocklast = 0;
            $curcm = null;
            for ($i = 0; $i < $n; $i++) {
                $cm = $cmseq[$i];
                $t = $tseq[$i];
                if ($curcm === null) {
                    $curcm = $cm;
                    $blockstart = $t;
                    $blocklast = $t;
                    continue;
                }
                $gap = $t - $blocklast;
                if ($cm !== $curcm || $gap > 600) {
                    $dwell = $blocklast - $blockstart;
                    $blocks++;
                    if ($dwell < 8) $short++;
                    $curcm = $cm;
                    $blockstart = $t;
                    $blocklast = $t;
                } else {
                    $blocklast = $t;
                }
            }
            if ($curcm !== null) {
                $dwell = $blocklast - $blockstart;
                $blocks++;
                if ($dwell < 8) $short++;
            }
            if ($blocks > 0) {
                $sdr_pop[] = $short / $blocks;
            }

            // W1S and EDR.
            $sessions = self::build_sessions($eventsbyuser[$uid] ?? []);
            if (!empty($sessions)) {
                $course = $DB->get_record('course', ['id' => $courseid], 'id,startdate', MUST_EXIST);
                $start = (int)$course->startdate;
                $week1 = [];
                $week2 = [];
                foreach ($sessions as $s) {
                    $week = (int)floor(max(0, ($s['start'] - $start)) / (7 * 86400)) + 1;
                    if ($week === 1) $week1[] = $s['dur'];
                    if ($week === 2 || $week === 3) $week2[] = $s['dur'];
                }
                if (!empty($week1) && !empty($week2)) {
                    $w1 = utils::median($week1);
                    $w23 = utils::median($week2);
                    $w1s_pop[] = $w1 / max(1.0, $w23);
                }
                // EDR: active week1 but not week2.
                $activew1 = !empty($week1);
                $activew2 = false;
                foreach ($sessions as $s) {
                    $week = (int)floor(max(0, ($s['start'] - $start)) / (7 * 86400)) + 1;
                    if ($week === 2) { $activew2 = true; break; }
                }
                if ($activew1) {
                    $edr_flags[] = $activew2 ? 0 : 1;
                }
            }
        }

        // Segment raw proxies that need DB tables.
        $quizmetrics = self::quiz_metrics($courseid, $since);
        $assignmetrics = self::assign_metrics($courseid, $since);

        // Resource overload metrics.
        [$rdi_raw, $oru_raw] = self::resource_metrics($courseid, $viewsbyuser, $since);

        // Hidden prerequisites metrics.
        [$psr_raw, $dfs_raw] = self::prereq_metrics($courseid, $viewsbyuser);

        // Deadline metrics.
        [$las_raw, $err_raw] = self::deadline_metrics($courseid, $since);

        // Now normalize indicator raw values into 0..100 using robust population scoring.
        $rr = utils::robust_score(utils::median($rr_pop), $rr_pop, 'high');
        $bf = utils::robust_score(utils::median($bf_pop), $bf_pop, 'high');
        // JC raw is count; scale to ratio by dividing by distinct modules median.
        $jc_med = utils::median($jc_pop);
        $jc_ratio_pop = [];
        foreach ($jc_pop as $v) {
            $jc_ratio_pop[] = $v / 50.0; // coarse scaling.
        }
        $jc = utils::robust_score($jc_med / 50.0, $jc_ratio_pop, 'high');
        $cor = utils::robust_score(utils::median($cor_pop), $cor_pop, 'high');
        $lar = utils::robust_score(utils::median($lar_pop), $lar_pop, 'high');
        $rsv = utils::robust_score(utils::median($rsv_pop), $rsv_pop, 'high');
        $sdr = utils::robust_score(utils::median($sdr_pop), $sdr_pop, 'high');
        $w1s = utils::robust_score(utils::median($w1s_pop), $w1s_pop, 'high');
        $edr = count($edr_flags) ? (array_sum($edr_flags)/count($edr_flags))*100.0 : 0.0;

        // Quiz/Assign indicators already 0..1-ish ratios from helpers.
        $ada = $quizmetrics['ada'] * 100.0;
        $crr = $quizmetrics['crr'] * 100.0;
        $adv = $assignmetrics['adv'] * 100.0;
        $lsc = $assignmetrics['lsc'] * 100.0;

        $rdi = utils::clamp($rdi_raw * 100.0, 0.0, 100.0);
        $oru = utils::clamp($oru_raw * 100.0, 0.0, 100.0);

        $psr = utils::clamp($psr_raw * 100.0, 0.0, 100.0);
        $dfs = utils::clamp($dfs_raw * 100.0, 0.0, 100.0);

        $las = utils::clamp($las_raw * 100.0, 0.0, 100.0);
        $err = utils::clamp($err_raw * 100.0, 0.0, 100.0);

        // Additional proxies for SD (session duration per module) and others we approximate from SDR/ RR.
        // SD proxy: longer dwell + higher revisits.
        $sd_proxy = utils::clamp((($sdr / 100.0) * 0.3 + ($rr / 100.0) * 0.7), 0.0, 1.0) * 100.0;

        // TCD & AR: use abandonment proxy from lack of repeated views + short dwell.
        $tcd = utils::clamp((($rr/100.0) * 0.4 + ($sdr/100.0) * 0.6), 0.0, 1.0) * 100.0;
        $ar = utils::clamp((($edr/100.0)*0.5 + ($sdr/100.0)*0.5), 0.0, 1.0) * 100.0;

        // Segment scores using earlier weights.
        $segments = [];
        $segments['f01'] = (int)round($sd_proxy * 0.6 + $rr * 0.4);
        $segments['f02'] = (int)round($tcd * 0.7 + $ar * 0.3);
        $segments['f03'] = (int)round($bf * 0.5 + $jc * 0.5);
        $segments['f04'] = (int)round($w1s * 0.6 + $edr * 0.4);

        // Passive presence: inactive enrollment and login-to-activity.
        $ier = self::inactive_enrollment_ratio($enrolledusers, array_keys($eventsbyuser));
        $segments['f05'] = (int)round($lar * 0.7 + ($ier * 100.0) * 0.3);

        $segments['f06'] = (int)round($ada * 0.6 + $crr * 0.4);
        $segments['f07'] = (int)round($adv * 0.5 + $lsc * 0.5);
        $segments['f08'] = (int)round($cor * 0.6 + $bf * 0.4);
        $segments['f09'] = (int)round($rdi * 0.7 + (100.0 - $oru) * 0.3);
        $segments['f10'] = (int)round($psr * 0.5 + $dfs * 0.5);
        $segments['f11'] = (int)round($rsv * 0.6 + $sdr * 0.4);
        $segments['f12'] = (int)round($las * 0.7 + $err * 0.3);

        foreach ($segments as $k => $v) {
            $segments[$k] = (int)utils::clamp((float)$v, 0.0, 100.0);
        }

        $weights = [
            'f01' => 1.2,
            'f04' => 1.2,
            'f10' => 1.1,
        ];
        $sumw = 0.0;
        $sum = 0.0;
        foreach ($segments as $k => $v) {
            $w = $weights[$k] ?? 1.0;
            $sumw += $w;
            $sum += $w * $v;
        }
        $overall = (int)round($sumw > 0 ? ($sum / $sumw) : 0);

        // Top driver.
        arsort($segments);
        $top = array_key_first($segments);

        return [
            'generated_at' => time(),
            'window_days' => $windowdays,
            'overall' => $overall,
            'segments' => $segments,
            'meta' => [
                'top_driver' => $top,
            ],
        ];
    }

    private static function build_sessions(array $times): array {
        if (empty($times)) {
            return [];
        }
        sort($times, SORT_NUMERIC);
        $sessions = [];
        $start = $times[0];
        $last = $times[0];
        foreach ($times as $t) {
            if (($t - $last) > 1800) {
                $sessions[] = ['start' => $start, 'end' => $last, 'dur' => max(0, $last - $start)];
                $start = $t;
            }
            $last = $t;
        }
        $sessions[] = ['start' => $start, 'end' => $last, 'dur' => max(0, $last - $start)];
        return $sessions;
    }

    private static function get_enrolled_userids(int $courseid): array {
        global $DB;
        $sql = "
            SELECT DISTINCT ue.userid
              FROM {user_enrolments} ue
              JOIN {enrol} e ON e.id = ue.enrolid
             WHERE e.courseid = :courseid
        ";
        $recs = $DB->get_records_sql($sql, ['courseid' => $courseid]);
        return array_map(fn($r) => (int)$r->userid, $recs);
    }

    private static function inactive_enrollment_ratio(array $enrolled, array $activeusers): float {
        $enrolledcount = count($enrolled);
        if ($enrolledcount === 0) return 0.0;
        $active = array_fill_keys($activeusers, true);
        $inactive = 0;
        foreach ($enrolled as $u) {
            if (!isset($active[$u])) $inactive++;
        }
        return $inactive / $enrolledcount;
    }

    private static function quiz_metrics(int $courseid, int $since): array {
        global $DB;
        $mgr = $DB->get_manager();
        $ada = 0.0;
        $crr = 0.0;
        if ($mgr->table_exists('quiz_attempts') && $mgr->table_exists('quiz')) {
            $sql = "
                SELECT qa.userid, qa.quiz, qa.timestart, qa.timefinish, qa.state
                  FROM {quiz_attempts} qa
                  JOIN {quiz} q ON q.id = qa.quiz
                 WHERE q.course = :courseid
                   AND qa.timestart >= :since
            ";
            $rows = $DB->get_records_sql($sql, ['courseid' => $courseid, 'since' => $since]);
            if (!empty($rows)) {
                $durations = [];
                $attemptsperuserquiz = [];
                $finishedperuserquiz = [];
                foreach ($rows as $r) {
                    $key = $r->userid . ':' . $r->quiz;
                    $attemptsperuserquiz[$key] = ($attemptsperuserquiz[$key] ?? 0) + 1;
                    if ($r->state === 'finished' && $r->timefinish && $r->timefinish > $r->timestart) {
                        $finishedperuserquiz[$key] = ($finishedperuserquiz[$key] ?? 0) + 1;
                        $durations[] = max(1, (int)$r->timefinish - (int)$r->timestart);
                    }
                }
                // ADA: high proportion of very short attempts (<20s) or extremely long (>30min).
                if (!empty($durations)) {
                    $short = 0;
                    $long = 0;
                    foreach ($durations as $d) {
                        if ($d < 20) $short++;
                        if ($d > 1800) $long++;
                    }
                    $ada = utils::clamp(($short + $long) / count($durations), 0.0, 1.0);
                }
                // CRR: retries per completion.
                $ratios = [];
                foreach ($attemptsperuserquiz as $key => $cnt) {
                    $fin = $finishedperuserquiz[$key] ?? 0;
                    $retries = max(0, $cnt - 1);
                    $ratio = $retries / max(1, $fin);
                    $ratios[] = $ratio;
                }
                if (!empty($ratios)) {
                    $med = utils::median($ratios);
                    // Map 0..3 retries per completion to 0..1.
                    $crr = utils::clamp($med / 3.0, 0.0, 1.0);
                }
            }
        }
        return ['ada' => $ada, 'crr' => $crr];
    }

    private static function assign_metrics(int $courseid, int $since): array {
        global $DB;
        $mgr = $DB->get_manager();
        $adv = 0.0;
        $lsc = 0.0;
        if ($mgr->table_exists('assign') && $mgr->table_exists('assign_submission')) {
            // ADV: description views per submission (proxy via logstore for assign cm views is hard; we approximate using submissions count).
            $sqlsubs = "
                SELECT a.id AS assignid, a.duedate, COUNT(s.id) AS subs,
                       SUM(CASE WHEN s.timemodified >= (a.duedate - 172800) AND s.timemodified <= a.duedate AND a.duedate > 0 THEN 1 ELSE 0 END) AS late48
                  FROM {assign} a
                  LEFT JOIN {assign_submission} s ON s.assignment = a.id
                 WHERE a.course = :courseid
                   AND (s.timemodified IS NULL OR s.timemodified >= :since)
                 GROUP BY a.id, a.duedate
            ";
            $rows = $DB->get_records_sql($sqlsubs, ['courseid' => $courseid, 'since' => $since]);
            if (!empty($rows)) {
                $ratios_adv = [];
                $ratios_lsc = [];
                foreach ($rows as $r) {
                    $subs = (int)$r->subs;
                    if ($subs <= 0) continue;
                    // Assume 1.5 description views per submission as baseline; more re-reads hint unclear expectations.
                    // Without reliable per-assign view logs, we use a conservative proxy: many resubmissions will increase ADV.
                    $resubs = $DB->count_records('assign_submission', ['assignment' => $r->assignid, 'status' => 'submitted']);
                    $raw = utils::clamp(($resubs / max(1, $subs)) / 2.0, 0.0, 1.0);
                    $ratios_adv[] = $raw;

                    if ((int)$r->duedate > 0) {
                        $ratios_lsc[] = utils::clamp(((int)$r->late48) / $subs, 0.0, 1.0);
                    }
                }
                if (!empty($ratios_adv)) $adv = utils::median($ratios_adv);
                if (!empty($ratios_lsc)) $lsc = utils::median($ratios_lsc);
            }
        }
        return ['adv' => $adv, 'lsc' => $lsc];
    }

    private static function resource_metrics(int $courseid, array $viewsbyuser, int $since): array {
        global $DB;
        // Resource density per section.
        $resources = ['resource', 'page', 'url', 'book', 'folder', 'file'];
        $sql = "
            SELECT cm.id, cm.section, m.name AS modname, cm.completion, cm.availability
              FROM {course_modules} cm
              JOIN {modules} m ON m.id = cm.module
             WHERE cm.course = :courseid
        ";
        $mods = $DB->get_records_sql($sql, ['courseid' => $courseid]);
        $sec = [];
        $opt = [];
        foreach ($mods as $m) {
            $section = (int)$m->section;
            $sec[$section]['total'] = ($sec[$section]['total'] ?? 0) + 1;
            $isres = in_array($m->modname, $resources, true);
            if ($isres) {
                $sec[$section]['res'] = ($sec[$section]['res'] ?? 0) + 1;
            }
            // Optional: no completion tracking.
            if ($m->completion == 0 && $isres) {
                $opt[] = (int)$m->id;
            }
        }
        $densities = [];
        foreach ($sec as $s) {
            $densities[] = ($s['res'] ?? 0) / max(1, ($s['total'] ?? 0));
        }
        $rdi = !empty($densities) ? utils::median($densities) : 0.0;

        // Optional resource utilization: share of optional resources opened by any user.
        $opened = [];
        $optset = array_fill_keys($opt, true);
        foreach ($viewsbyuser as $uid => $seq) {
            foreach ($seq as $e) {
                $cmid = $e['cmid'];
                if (isset($optset[$cmid])) $opened[$cmid] = true;
            }
        }
        $oru = count($opt) > 0 ? (count($opened) / count($opt)) : 0.0;
        return [$rdi, $oru];
    }

    private static function prereq_metrics(int $courseid, array $viewsbyuser): array {
        global $DB;
        // Parse availability JSON to find prerequisite cm dependencies.
        $sql = "
            SELECT id, availability
              FROM {course_modules}
             WHERE course = :courseid
               AND availability IS NOT NULL
               AND availability <> ''
        ";
        $rows = $DB->get_records_sql($sql, ['courseid' => $courseid]);
        $edges = [];
        foreach ($rows as $r) {
            $json = $r->availability;
            if (!$json) continue;
            $data = json_decode($json);
            if (!$data) continue;
            // Recursively find 'cm' conditions.
            $deps = self::find_cm_deps($data);
            foreach ($deps as $dep) {
                $edges[] = ['a' => (int)$dep, 'b' => (int)$r->id];
            }
        }
        if (empty($edges)) {
            return [0.0, 0.0];
        }
        // Build view sets per user.
        $viewset = [];
        foreach ($viewsbyuser as $uid => $seq) {
            foreach ($seq as $e) {
                $viewset[$uid][(int)$e['cmid']] = true;
            }
        }
        $psr_edges = [];
        $dfs_edges = [];
        foreach ($edges as $edge) {
            $a = $edge['a'];
            $b = $edge['b'];
            $viewedb = 0;
            $skipped = 0;
            foreach ($viewset as $uid => $set) {
                if (!empty($set[$b])) {
                    $viewedb++;
                    if (empty($set[$a])) $skipped++;
                }
            }
            if ($viewedb > 0) {
                $psr_edges[] = $skipped / $viewedb;
            }
            // DFS proxy: if skip ratio high, treat as friction spike.
            $dfs_edges[] = utils::clamp(($skipped / max(1, $viewedb)) * 1.5, 0.0, 1.0);
        }
        $psr = !empty($psr_edges) ? utils::median($psr_edges) : 0.0;
        $dfs = !empty($dfs_edges) ? utils::median($dfs_edges) : 0.0;
        return [$psr, $dfs];
    }

    private static function find_cm_deps($node): array {
        $deps = [];
        if (is_object($node)) {
            foreach ($node as $k => $v) {
                if ($k === 'cm' && is_numeric($v)) {
                    $deps[] = (int)$v;
                } else {
                    $deps = array_merge($deps, self::find_cm_deps($v));
                }
            }
        } elseif (is_array($node)) {
            foreach ($node as $v) {
                $deps = array_merge($deps, self::find_cm_deps($v));
            }
        }
        return array_values(array_unique($deps));
    }

    private static function deadline_metrics(int $courseid, int $since): array {
        global $DB;
        $mgr = $DB->get_manager();
        $deadlines = [];
        if ($mgr->table_exists('assign')) {
            $rows = $DB->get_records('assign', ['course' => $courseid], 'id', 'id,duedate');
            foreach ($rows as $r) {
                if (!empty($r->duedate)) $deadlines[] = (int)$r->duedate;
            }
        }
        if ($mgr->table_exists('quiz')) {
            $rows = $DB->get_records('quiz', ['course' => $courseid], 'id', 'id,timeclose');
            foreach ($rows as $r) {
                if (!empty($r->timeclose)) $deadlines[] = (int)$r->timeclose;
            }
        }
        if (empty($deadlines)) {
            return [0.0, 0.0];
        }
        // Count course events around each deadline.
        $sql = "
            SELECT timecreated
              FROM {logstore_standard_log}
             WHERE courseid = :courseid
               AND userid > 0
               AND timecreated >= :since
        ";
        $times = array_map(fn($r) => (int)$r->timecreated, $DB->get_records_sql($sql, ['courseid' => $courseid, 'since' => $since]));
        sort($times, SORT_NUMERIC);

        $las_vals = [];
        foreach ($deadlines as $d) {
            $last48 = self::count_in_range($times, $d - 172800, $d);
            $prev = self::count_in_range($times, $d - 604800, $d - 172800);
            if (($last48 + $prev) > 0) {
                $las_vals[] = $last48 / max(1, $prev);
            }
        }
        $las = !empty($las_vals) ? utils::clamp(utils::median($las_vals) / 3.0, 0.0, 1.0) : 0.0;

        // ERR proxy: last-minute resubmissions / retries. Approx via log volume in last 6h.
        $err_vals = [];
        foreach ($deadlines as $d) {
            $last6h = self::count_in_range($times, $d - 21600, $d);
            $prev6h = self::count_in_range($times, $d - 43200, $d - 21600);
            if (($last6h + $prev6h) > 0) {
                $err_vals[] = $last6h / max(1, $prev6h);
            }
        }
        $err = !empty($err_vals) ? utils::clamp(utils::median($err_vals) / 3.0, 0.0, 1.0) : 0.0;

        return [$las, $err];
    }

    private static function count_in_range(array $sortedtimes, int $from, int $to): int {
        // Binary search bounds.
        $lo = self::lower_bound($sortedtimes, $from);
        $hi = self::lower_bound($sortedtimes, $to + 1);
        return max(0, $hi - $lo);
    }

    private static function lower_bound(array $a, int $x): int {
        $lo = 0;
        $hi = count($a);
        while ($lo < $hi) {
            $mid = intdiv($lo + $hi, 2);
            if ($a[$mid] < $x) {
                $lo = $mid + 1;
            } else {
                $hi = $mid;
            }
        }
        return $lo;
    }
}
