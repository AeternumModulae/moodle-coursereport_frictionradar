<?php
namespace tool_frictionradar\friction;

defined('MOODLE_INTERNAL') || die();

/**
 * F10 – Hidden Dependencies / Versteckte Voraussetzungen
 *
 * Measures how often access restrictions create implicit prerequisites.
 */
class f10_hidden_dependencies extends abstract_friction {

    public function get_key(): string {
        return 'f10';
    }

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

            // B – No visible explanation text
            if (empty($availability['showc'])) {
                $noinfo++;
            }

            // C – Multiple dependency conditions
            if (count($conditions) > 1) {
                $chains++;
            }
        }

        $A = $restricted / max(1, $total);
        $B = $noinfo / max(1, $restricted);
        $C = $chains / max(1, $restricted);

        $score = $this->clamp(
            (int)round(
                100 * (0.4 * $A + 0.35 * $B + 0.25 * $C)
            )
        );

        return [
            'score' => $score,
            'breakdown' => [
                'formula' =>
                    "score = clamp( round( 100 * (0.4*A + 0.35*B + 0.25*C) ), 0, 100 )\n\n" .
                    "A = activities with access restrictions\n" .
                    "B = restricted activities without visible explanation\n" .
                    "C = chained dependency conditions",
                'inputs' => [
                    ['key'=>'total','label'=>'Visible activities','value'=>$total],
                    ['key'=>'restricted','label'=>'Activities with restrictions','value'=>$restricted],
                    ['key'=>'A','label'=>'Restriction ratio (0..1)','value'=>round($A,3)],
                    ['key'=>'B','label'=>'Restrictions without explanation (0..1)','value'=>round($B,3)],
                    ['key'=>'C','label'=>'Chained dependencies (0..1)','value'=>round($C,3)],
                ],
                'notes' => $this->str('notes_f10', $windowdays),
            ],
        ];
    }

    private function empty_result(int $windowdays): array {
        return [
            'score' => 0,
            'breakdown' => [
                'formula' => 'No access restrictions detected.',
                'inputs' => [],
                'notes' => $this->str('notes_f10', $windowdays),
            ],
        ];
    }
}
