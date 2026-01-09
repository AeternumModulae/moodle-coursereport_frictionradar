<?php
namespace tool_frictionradar\friction;

defined('MOODLE_INTERNAL') || die();

/**
 * F04 – Overambitious Entry / Überambitionierter Einstieg
 *
 * Measures how demanding the initial phase of a course is.
 */
class f04_overambitious_entry extends abstract_friction {

    public function get_key(): string {
        return 'f04';
    }

    public function calculate(int $courseid, int $windowdays): array {

        // Number of sections considered "entry phase"
        $entrysections = 2;

        $A = $this->mandatory_activities_in_entry($courseid, $entrysections); // 0..1
        $B = $this->early_workload_proxy($courseid, $entrysections);          // 0..1
        $C = $this->entry_content_complexity($courseid, $entrysections);      // 0..1

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
                    "A = mandatory activities in entry phase\n" .
                    "B = early workload proxy\n" .
                    "C = content complexity in entry phase",
                'inputs' => [
                    ['key'=>'A','label'=>'Mandatory activities (normalized)','value'=>round($A,3)],
                    ['key'=>'B','label'=>'Early workload proxy (normalized)','value'=>round($B,3)],
                    ['key'=>'C','label'=>'Entry content complexity (normalized)','value'=>round($C,3)],
                    ['key'=>'sections','label'=>'Entry sections considered','value'=>$entrysections],
                ],
                'notes' => $this->str('notes_f04', $windowdays),
            ],
        ];
    }

    /**
     * A – Mandatory activities in first N sections.
     * Normalized using /8 (very demanding start).
     */
    private function mandatory_activities_in_entry(int $courseid, int $sections): float {
        global $DB;

        $sql = "SELECT COUNT(cm.id)
                  FROM {course_modules} cm
                  JOIN {course_sections} cs ON cs.id = cm.section
                 WHERE cs.course = :courseid
                   AND cs.section BETWEEN 0 AND :maxsection
                   AND cm.visible = 1
                   AND cm.deletioninprogress = 0
                   AND cm.completion <> 0";

        $count = (int)$DB->get_field_sql($sql, [
            'courseid' => $courseid,
            'maxsection' => $sections,
        ]);

        return min(1.0, max(0.0, $count / 8.0));
    }

    /**
     * B – Early workload proxy.
     * Counts "demanding" activity types in entry sections.
     */
    private function early_workload_proxy(int $courseid, int $sections): float {
        global $DB;

        $demanding = [
            'assign','quiz','workshop','lesson','scorm','lti','data','feedback'
        ];

        list($insql, $params) = $DB->get_in_or_equal($demanding, SQL_PARAMS_NAMED);

        $sql = "SELECT COUNT(cm.id)
                  FROM {course_modules} cm
                  JOIN {modules} m ON m.id = cm.module
                  JOIN {course_sections} cs ON cs.id = cm.section
                 WHERE cs.course = :courseid
                   AND cs.section BETWEEN 0 AND :maxsection
                   AND cm.visible = 1
                   AND cm.deletioninprogress = 0
                   AND m.name $insql";

        $params['courseid'] = $courseid;
        $params['maxsection'] = $sections;

        $count = (int)$DB->get_field_sql($sql, $params);

        // Normalize: 6 demanding items early is already heavy.
        return min(1.0, max(0.0, $count / 6.0));
    }

    /**
     * C – Entry content complexity (text + structure).
     */
    private function entry_content_complexity(int $courseid, int $sections): float {
        global $DB;

        $sectionsdata = $DB->get_records_select(
            'course_sections',
            'course = :courseid AND section BETWEEN 0 AND :maxsection',
            ['courseid'=>$courseid, 'maxsection'=>$sections],
            '',
            'id, summary'
        );

        $text = '';
        foreach ($sectionsdata as $s) {
            $text .= ' ' . strip_tags((string)$s->summary);
        }

        $words = preg_split('/\s+/u', trim($text), -1, PREG_SPLIT_NO_EMPTY);
        $wordcount = is_array($words) ? count($words) : 0;

        if ($wordcount === 0) {
            return 0.0;
        }

        $longwords = 0;
        foreach ($words as $w) {
            if (mb_strlen($w) >= 12) {
                $longwords++;
            }
        }

        $ratio = $longwords / $wordcount;

        // Normalize: 15% long words is already demanding.
        return min(1.0, max(0.0, $ratio / 0.15));
    }
}
