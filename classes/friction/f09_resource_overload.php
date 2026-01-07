<?php
namespace tool_frictionradar\friction;

defined('MOODLE_INTERNAL') || die();

/**
 * F09 – Resource Overload / Ressourcenüberversorgung
 *
 * Measures whether learners are flooded with resources relative to structure and activities.
 */
class f09_resource_overload extends abstract_friction {

    public function get_key(): string {
        return 'f09';
    }

    public function calculate(int $courseid, int $windowdays): array {

        $stats = $this->resource_stats($courseid);

        $A = $this->resource_density($stats);    // 0..1
        $B = $this->resource_share($stats);      // 0..1
        $C = $this->resource_redundancy($stats); // 0..1

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
                    "A = resource density\n" .
                    "B = resource share\n" .
                    "C = resource redundancy proxy",
                'inputs' => [
                    ['key'=>'sections','label'=>'Non-empty sections','value'=>$stats['sections']],
                    ['key'=>'modules_total','label'=>'Visible modules (total)','value'=>$stats['modules']],
                    ['key'=>'resources_total','label'=>'Resources (total)','value'=>$stats['resources']],
                    ['key'=>'resources_file','label'=>'File resources (resource)','value'=>$stats['resourcefiles']],
                    ['key'=>'A','label'=>'Resource density (0..1)','value'=>round($A,3)],
                    ['key'=>'B','label'=>'Resource share (0..1)','value'=>round($B,3)],
                    ['key'=>'C','label'=>'Redundancy proxy (0..1)','value'=>round($C,3)],
                ],
                'notes' => $this->str('notes_f09', $windowdays),
            ],
        ];
    }

    /**
     * Collect basic stats for resource overload.
     */
    private function resource_stats(int $courseid): array {
        global $DB;

        // Non-empty sections & total visible modules.
        $sql = "SELECT
                    cs.id AS sectionid,
                    COUNT(cm.id) AS modulecount
                FROM {course_sections} cs
                LEFT JOIN {course_modules} cm
                       ON cm.section = cs.id
                      AND cm.visible = 1
                      AND cm.deletioninprogress = 0
                WHERE cs.course = :courseid
                  AND cs.section >= 0
                GROUP BY cs.id";

        $rows = $DB->get_records_sql($sql, ['courseid'=>$courseid]);

        $sections = 0;
        $modules = 0;

        foreach ($rows as $r) {
            $count = (int)$r->modulecount;
            if ($count > 0) {
                $sections++;
                $modules += $count;
            }
        }

        // Count resource-type modules.
        // Define "resources" broadly: resource (file), page, url, book, folder, label.
        $sql = "SELECT
                    SUM(CASE WHEN m.name IN ('resource','page','url','book','folder','label') THEN 1 ELSE 0 END) AS resources,
                    SUM(CASE WHEN m.name = 'resource' THEN 1 ELSE 0 END) AS resourcefiles
                FROM {course_modules} cm
                JOIN {modules} m ON m.id = cm.module
               WHERE cm.course = :courseid
                 AND cm.visible = 1
                 AND cm.deletioninprogress = 0";

        $r = $DB->get_record_sql($sql, ['courseid'=>$courseid]);

        $resources = (int)($r->resources ?? 0);
        $resourcefiles = (int)($r->resourcefiles ?? 0);

        return [
            'sections' => $sections,
            'modules' => $modules,
            'resources' => $resources,
            'resourcefiles' => $resourcefiles,
        ];
    }

    /**
     * A – Resource density (resources per non-empty section).
     * Normalize: 6 resources/section is "heavy".
     */
    private function resource_density(array $stats): float {
        $sections = max(1, (int)$stats['sections']);
        $resources = (int)$stats['resources'];

        $density = $resources / $sections;
        return min(1.0, max(0.0, $density / 6.0));
    }

    /**
     * B – Resource share (resources / all modules).
     * Normalize: >70% resources means the course is mostly materials, little activity.
     */
    private function resource_share(array $stats): float {
        $modules = (int)$stats['modules'];
        if ($modules <= 0) {
            return 0.0;
        }

        $share = $stats['resources'] / $modules;
        return min(1.0, max(0.0, $share / 0.70));
    }

    /**
     * C – Redundancy proxy (many file resources among resources).
     * Normalize: if >60% of resources are plain file resources, redundancy risk increases.
     */
    private function resource_redundancy(array $stats): float {
        $resources = (int)$stats['resources'];
        if ($resources <= 0) {
            return 0.0;
        }

        $ratio = $stats['resourcefiles'] / $resources;
        return min(1.0, max(0.0, $ratio / 0.60));
    }
}
