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

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Friction Radar';
$string['plugindescription'] = 'Early signals of learning friction based on aggregated course activity.';
$string['navitem'] = 'Friction Radar';
$string['page_title'] = 'Learning friction overview';
$string['page_subtitle'] = 'Aggregated course-level signals (rolling 6-week window).';
$string['overall_score'] = 'Overall friction score';
$string['friction_clock_aria'] = 'Friction clock';
$string['generated_at'] = 'Generated';
$string['window'] = 'Window';
$string['days'] = 'days';
$string['no_data'] = 'Not enough data yet. Values will appear after the nightly cache warm-up.';
$string['warmcache_now'] = 'Generate values now';
$string['warmcache_done'] = 'Cache has been regenerated for this course.';
$string['task_warm_cache'] = 'Friction Radar: queue nightly cache warmers';
$string['task_warm_course'] = 'Friction Radar: warm course cache';
$string['frictionradar:view'] = 'View Friction Radar report';
$string['frictionradar:export'] = 'Export Friction Radar report';

$string['friction_f01'] = 'Cognitive overload';
$string['friction_f02'] = 'Didactic bottlenecks';
$string['friction_f03'] = 'Navigational chaos';
$string['friction_f04'] = 'Overloaded entry point';
$string['friction_f05'] = 'Passive presence effect';
$string['friction_f06'] = 'Zombie quizzes';
$string['friction_f07'] = 'Unclear expectations';
$string['friction_f08'] = 'Structural disorientation';
$string['friction_f09'] = 'Resource overload';
$string['friction_f10'] = 'Hidden prerequisites';
$string['friction_f11'] = 'Frustrated scrolling';
$string['friction_f12'] = 'Deadline panic zone';

$string['explain_f01'] = 'Cognitive overload describes how much simultaneous mental effort a course requires from learners. The value is calculated based on the number of parallel activities, the density of mandatory resources within course sections, and the average textual complexity of learning materials during the analysis window. High scores indicate that learners are confronted with too much information at the same time.';
$string['explain_f02'] = 'Didactic bottlenecks indicate weaknesses in the instructional design of a course. The score is derived from frequent changes between activity types, missing didactic progression across sections, and inconsistent use of learning formats. High values suggest that the learning path lacks methodological coherence.';
$string['explain_f03'] = 'Navigation Chaos reflects how difficult it is for learners to orient themselves within the course structure. The value is calculated from the depth of the section hierarchy, the number of cross-references between distant sections, and frequent navigation jumps. High scores indicate disorientation and increased cognitive navigation effort.';
$string['explain_f04'] = 'Overambitious Entry measures how demanding the initial phase of a course is. The score is based on the number of mandatory activities and required resources in the early sections, as well as the expected workload shortly after course start. High values indicate that learners face high expectations too early.';
$string['explain_f05'] = 'Participation Theatre identifies apparent participation without meaningful engagement. The value is calculated from high access or login activity combined with low interaction depth, such as minimal forum contributions or repeated passive content views. High scores indicate superficial participation rather than active learning.';
$string['explain_f06'] = 'Zombie quizzes describe assessment activities that are completed mechanically with little learning effect. The score is derived from repeated quiz attempts with minimal performance improvement, very short completion times, and low variation in responses. High values indicate disengaged or automated quiz behaviour.';
$string['explain_f07'] = 'Unclear expectations measure how transparent course requirements and assessment criteria are. The value is calculated from missing or late assignment descriptions, unclear grading information, and frequent deadline changes. High scores indicate uncertainty about what is expected from learners.';
$string['explain_f08'] = 'Structure Paradox describes courses that appear formally well structured but are difficult to understand in practice. The score is calculated from deep section nesting combined with low activity density and frequent backtracking in navigation. High values indicate formal structure without functional clarity.';
$string['explain_f09'] = 'Resource overload measures the extent to which learners are confronted with an excessive amount of learning materials. The value is derived from the number of files, pages, and external links per section, weighted against actual learner interaction with these resources. High scores indicate an oversupply of materials with low effective usage.';
$string['explain_f10'] = 'Hidden Dependencies capture implicit prerequisites that are not explicitly communicated to learners. The score is calculated from repeated access attempts to restricted activities, unmet access conditions, and dependency chains between activities without clear guidance. High values indicate invisible barriers within the learning path.';
$string['explain_f11'] = 'Frust Scroll measures inefficient content consumption behaviour. The value is derived from long scrolling sessions, repeated page views without interaction, and rapid navigation through extensive content pages. High scores indicate frustration caused by poorly structured or overly long content.';
$string['explain_f12'] = 'Deadline Panic measures time pressure resulting from clustered or poorly distributed deadlines. The score is calculated from overlapping due dates, short submission intervals, and increased last-minute submission activity. High values indicate stress-inducing deadline structures.';

$string['what_to_do'] = 'What to do if the value is high:';

$string['notes_f01'] = 'Calculated over the last {$a} days. A increases when many mandatory activities occur in parallel. B increases when mandatory activities are supported by many mandatory resources. C increases with high average textual complexity of activity descriptions and content.';
$string['notes_f02'] = 'Calculated over the last {$a} days. A increases when activity types change frequently. B increases when demanding activities lack nearby supporting resources. C is currently a placeholder (0.0) until attempt/delay metrics are implemented.';
$string['notes_f03'] = 'Calculated over the last {$a} days. A increases with many non-empty sections (fragmentation). B increases when activities are unevenly distributed across sections. C increases when many different activity types are mixed (type entropy).';
$string['notes_f04'] = 'Calculated over the last {$a} days. A increases with many mandatory activities at the beginning of the course. B reflects early workload caused by demanding activities. C increases with complex introductory content.';
$string['notes_f05'] = 'Calculated over the last {$a} days. A counts students who repeatedly view the course but perform no substantive actions. B reflects low interaction depth (few meaningful actions per viewer). C captures the gap between viewers and engaged learners. Substantive actions are approximated from logstore_standard_log (e.g., created/submitted/posted).';
$string['notes_f06'] = 'Calculated over the last {$a} days. A increases when quizzes receive zero attempts (zombies). B increases when many attempts are not finished (abandonment). C increases when quizzes are attempted by very few learners (low participation). Attempts are derived from quiz_attempts and filtered to student-like roles.';
$string['notes_f07'] = 'Calculated over the last {$a} days. A increases when mandatory activities lack clear due dates. B increases when graded activities have no meaningful description. C increases when the course lacks a central overview or expectation anchor at the beginning.';
$string['notes_f08'] = 'Calculated over the last {$a} days. A increases with dense formal structure (many modules per section). B increases when many redundant structure elements (labels, books, folders) compete for attention. C increases when section sizes vary strongly, reducing structural predictability.';
$string['notes_f09'] = 'Calculated over the last {$a} days. A increases when there are many resources per section (resource density). B increases when resources dominate compared to activities (resource share). C increases when many resources are plain file resources (redundancy proxy).';
$string['notes_f10'] = 'Calculated over the last {$a} days. A increases when many activities have access restrictions. B increases when restricted activities do not show an explanation to learners. C increases when access depends on multiple chained conditions.';
$string['notes_f11'] = 'Calculated over the last {$a} days. A increases with overall course length and density. B increases when sections contain many items, forcing excessive scrolling. C increases when few navigational anchors (labels) structure the course.';
$string['notes_f12'] = 'Calculated over the last {$a} days. A increases with a high density of deadlines. B increases when deadlines cluster on the same day. C increases when deadlines are set with very short notice, leaving little time for preparation.';

$string['formula_f01'] = 'score = clamp( round( 100 * (0.5*A + 0.3*B + 0.2*C) ), 0, 100 )';
$string['formula_f02'] = 'score = clamp( round( 100 * (0.4*A + 0.35*B + 0.25*C) ), 0, 100 ); A = activity transition density; B = support gap ratio; C = retry & delay signal';
$string['formula_f03'] = 'score = clamp( round( 100 * (0.4*A + 0.35*B + 0.25*C) ), 0, 100 ); A = structural fragmentation; B = section load imbalance; C = module-type entropy';
$string['formula_f04'] = 'score = clamp( round( 100 * (0.45*A + 0.35*B + 0.20*C) ), 0, 100 ); A = mandatory activities in entry phase; B = early workload proxy; C = content complexity in entry phase';
$string['formula_f05'] = 'score = clamp( round( 100 * (0.5*A + 0.3*B + 0.2*C) ), 0, 100 ); A = passive viewer ratio; B = low interaction depth (inverse of avg substantive actions); C = engagement gap (viewers vs engaged)';
$string['formula_f06'] = 'score = clamp( round( 100 * (0.45*A + 0.35*B + 0.20*C) ), 0, 100 ); A = zombie ratio (quizzes with 0 attempts in window); B = abandonment ratio (1 - finished/total attempts in window); C = low participation ratio (quizzes with < {$a} learners in window)';
$string['formula_f07'] = 'score = clamp( round( 100 * (0.45*A + 0.35*B + 0.20*C) ), 0, 100 ); A = mandatory activities without due dates; B = graded activities without description; C = missing central expectation anchor';
$string['formula_f08'] = 'score = clamp( round( 100 * (0.4*A + 0.35*B + 0.25*C) ), 0, 100 ); A = structural overlay; B = redundant structure signals; C = structural inconsistency';
$string['formula_f09'] = 'score = clamp( round( 100 * (0.45*A + 0.35*B + 0.20*C) ), 0, 100 ); A = resource density; B = resource share; C = resource redundancy proxy';
$string['formula_f10'] = 'score = clamp( round( 100 * (0.4*A + 0.35*B + 0.25*C) ), 0, 100 ); A = activities with access restrictions; B = restricted activities without visible explanation; C = chained dependency conditions';
$string['formula_f10_empty'] = 'No access restrictions detected.';
$string['formula_f11'] = 'score = clamp( round( 100 * (0.4*A + 0.35*B + 0.25*C) ), 0, 100 ); A = overall course length; B = section overload; C = missing navigational anchors';
$string['formula_f12'] = 'score = clamp( round( 100 * (0.4*A + 0.35*B + 0.25*C) ), 0, 100 ); A = deadline density; B = deadline clustering; C = short-notice deadlines';
$string['formula_f12_empty'] = 'No deadlines detected.';

$string['input_f01_a'] = 'Parallel mandatory activities (avg per section)';
$string['input_f01_b'] = 'Mandatory resource density';
$string['input_f01_c'] = 'Average textual complexity (normalized)';
$string['input_f02_a'] = 'Activity transition density (0..1)';
$string['input_f02_b'] = 'Support gap ratio (0..1)';
$string['input_f02_c'] = 'Retry & delay signal (0..1)';
$string['input_f03_sections_nonempty'] = 'Non-empty sections (visible)';
$string['input_f03_modules_total'] = 'Visible modules (total)';
$string['input_f03_types_unique'] = 'Unique module types';
$string['input_f03_a'] = 'Structural fragmentation (0..1)';
$string['input_f03_b'] = 'Section load imbalance (0..1)';
$string['input_f03_c'] = 'Module-type entropy (0..1)';
$string['input_f04_a'] = 'Mandatory activities (normalized)';
$string['input_f04_b'] = 'Early workload proxy (normalized)';
$string['input_f04_c'] = 'Entry content complexity (normalized)';
$string['input_f04_sections'] = 'Entry sections considered';
$string['input_f05_min_views'] = 'Viewer threshold (min views)';
$string['input_f05_viewers'] = 'Viewers (>= min views)';
$string['input_f05_passive'] = 'Passive viewers (0 substantive)';
$string['input_f05_engaged'] = 'Engaged users (>=1 substantive)';
$string['input_f05_substantive_total'] = 'Total substantive actions';
$string['input_f05_avg_sub'] = 'Avg substantive per viewer';
$string['input_f05_a'] = 'Passive viewer ratio (0..1)';
$string['input_f05_b'] = 'Low interaction depth (0..1)';
$string['input_f05_c'] = 'Engagement gap (0..1)';
$string['input_f06_quizzes_total'] = 'Visible quizzes (total)';
$string['input_f06_zombies'] = 'Quizzes with 0 attempts (window)';
$string['input_f06_attempts_total'] = 'Attempts (window, students)';
$string['input_f06_attempts_finished'] = 'Finished attempts (window)';
$string['input_f06_low_participation'] = 'Quizzes with low participation';
$string['input_f06_a'] = 'Zombie ratio (0..1)';
$string['input_f06_b'] = 'Abandonment ratio (0..1)';
$string['input_f06_c'] = 'Low participation ratio (0..1)';
$string['input_f07_a'] = 'Mandatory activities without due date (ratio)';
$string['input_f07_b'] = 'Graded activities without description (ratio)';
$string['input_f07_c'] = 'Missing expectation anchor (0 or 1)';
$string['input_f08_sections'] = 'Non-empty sections';
$string['input_f08_modules'] = 'Visible modules';
$string['input_f08_structure_modules'] = 'Structure modules';
$string['input_f08_a'] = 'Structural overlay (0..1)';
$string['input_f08_b'] = 'Redundant structure signals (0..1)';
$string['input_f08_c'] = 'Structural inconsistency (0..1)';
$string['input_f09_sections'] = 'Non-empty sections';
$string['input_f09_modules_total'] = 'Visible modules (total)';
$string['input_f09_resources_total'] = 'Resources (total)';
$string['input_f09_resources_file'] = 'File resources (resource)';
$string['input_f09_a'] = 'Resource density (0..1)';
$string['input_f09_b'] = 'Resource share (0..1)';
$string['input_f09_c'] = 'Redundancy proxy (0..1)';
$string['input_f10_total'] = 'Visible activities';
$string['input_f10_restricted'] = 'Activities with restrictions';
$string['input_f10_a'] = 'Restriction ratio (0..1)';
$string['input_f10_b'] = 'Restrictions without explanation (0..1)';
$string['input_f10_c'] = 'Chained dependencies (0..1)';
$string['input_f11_sections'] = 'Non-empty sections';
$string['input_f11_modules'] = 'Visible modules';
$string['input_f11_avg_section_size'] = 'Average modules per section';
$string['input_f11_labels'] = 'Label modules';
$string['input_f11_a'] = 'Course length (0..1)';
$string['input_f11_b'] = 'Section overload (0..1)';
$string['input_f11_c'] = 'Missing anchors (0..1)';
$string['input_f12_deadlines'] = 'Deadlines considered';
$string['input_f12_a'] = 'Deadline density (0..1)';
$string['input_f12_b'] = 'Deadline clustering (0..1)';
$string['input_f12_c'] = 'Short-notice ratio (0..1)';

$string['privacy:metadata'] = 'The Friction Radar tool stores only aggregated, course-level scores in cache and does not store personal data.';

$string['ui_score'] = 'Score';
$string['ui_formula'] = 'Formula';
$string['ui_inputs'] = 'Calculation inputs';
$string['ui_param'] = 'Parameter';
$string['ui_value'] = 'Value';
$string['ui_notes'] = 'Notes';

$string['action_f01'] = 'A high value for Cognitive overload indicates that learners are confronted with too many mandatory activities, dense resources, or complex materials at the same time. To reduce this friction, focus on deliberate reduction and careful sequencing of learning demands. Start by reviewing the first course sections and identify which activities are truly essential. If multiple tasks serve a similar purpose, merge them or make some of them optional. Learners benefit far more from a small number of clearly explained activities than from a long checklist of obligations. Reduce textual complexity where possible. Long descriptions, nested instructions, and highly abstract explanations increase cognitive load. Break texts into shorter paragraphs, use headings, and explain expectations in simple and direct language. Supplement complex content with visuals, examples, or short videos. Finally, stagger demands over time. Avoid placing many mandatory tasks within the same week or section. A clear progression from simple to complex allows learners to build confidence before facing higher demands.';

$string['action_f02'] = 'Didactic bottlenecks occur when learning activities are poorly aligned with learning objectives or when instructions are unclear or misleading. Learners may complete tasks without understanding their purpose, leading to frustration and disengagement. Begin by checking whether each activity clearly supports a learning outcome. Explicitly state why an activity exists and what learners should gain from completing it. If an activity does not clearly contribute to a learning goal, consider revising or removing it. Improve task descriptions by clarifying required inputs, expected outputs, and assessment criteria. Avoid implicit assumptions about prior knowledge. Where possible, include short examples of a successful submission or typical mistakes to avoid. Consistency is crucial. Use similar formats, terminology, and structures across activities. When learners recognize patterns, they can focus on learning instead of deciphering instructions.';

$string['action_f03'] = 'Navigation Chaos arises when learners struggle to find materials, activities, or orientation cues within a course. This often results from inconsistent structure or excessive depth in the course layout. To reduce this friction, establish a clear and repeatable structure. Use a consistent naming scheme for sections and activities. For example, start each week or topic with an overview, followed by materials, and then activities. Limit unnecessary nesting. Deep hierarchies of folders, pages, and links increase the risk of learners getting lost. If content must be grouped, explain why and what learners are expected to do next. Use labels and section summaries to provide orientation. A short sentence explaining what a section contains and how it fits into the overall course can significantly reduce navigational effort.';

$string['action_f04'] = 'An overambitious entry means that learners are confronted with high demands immediately after the course starts. This can overwhelm learners before they have established routines or confidence. Review the first one or two sections of your course. Count how many mandatory activities and resources are required early on. Consider postponing complex tasks, assessments, or heavy reading to later sections. Use the opening phase to orient learners. Introduce course goals, structure, and expectations gradually. Low-stakes activities such as short introductions, simple quizzes, or guided walkthroughs help learners acclimate. Early success matters. Design initial activities so that most learners can complete them successfully, building confidence and reducing early dropout risk.';

$string['action_f05'] = 'Participation Theatre describes situations where learners are required to participate without meaningful impact or feedback. Activities exist primarily to demonstrate activity rather than to support learning. Evaluate whether participation activities lead to reflection, discussion, or knowledge construction. If forum posts or submissions are required, ensure that they receive feedback or are meaningfully integrated into subsequent activities. Reduce artificial participation requirements. Mandatory posts without interaction often lead to superficial contributions. Instead, encourage fewer but more focused contributions with clear prompts. Where possible, replace formal participation with authentic tasks. Collaborative documents, peer feedback, or optional discussion prompts often result in more genuine engagement.';

$string['action_f06'] = 'Zombie quizzes are assessments that are reused repeatedly without revision and provide little diagnostic or learning value. Learners may complete them mechanically without reflection. Review quiz questions for relevance and clarity. Remove outdated or ambiguous items and ensure that questions align with current course content. Use feedback strategically. Immediate, explanatory feedback transforms quizzes into learning tools. Even short explanations for correct and incorrect answers can significantly improve learning outcomes. Consider varying quiz formats. Mixing formative quizzes, practice attempts, and self-assessment questions reduces monotony and increases engagement.';

$string['action_f07'] = 'Unclear expectations arise when learners are unsure what is required to succeed. This includes vague grading criteria, missing descriptions, or implicit assumptions. Ensure that all graded activities include clear descriptions and, where applicable, grading criteria or rubrics. Learners should understand how their work will be evaluated before submitting it. Clarify workload expectations. Indicate approximate time requirements and submission formats to help learners plan their work and reduce anxiety. Revisit activities from a learner perspective. If expectations are obvious only to experienced instructors, they are likely unclear to students.';

$string['action_f08'] = 'Structural disorientation occurs when learners cannot form a clear mental model of the course structure. This often results from inconsistent organization or frequent structural changes. Maintain a stable course structure throughout the term. Avoid moving activities or renaming sections once learners have started working with them. Use recurring patterns. For example, each section could follow the same internal order: overview, materials, activities, assessment. Provide structural signals. Section summaries, visual separators, and consistent icon usage help learners recognize structure and reduce confusion.';

$string['action_f09'] = 'Resource overload is caused by an excessive number of files, links, and external resources. Learners may struggle to identify what is essential. Audit your resources regularly. Remove outdated or redundant materials and clearly mark optional resources as such. Prioritize quality over quantity. A small number of well-chosen resources is often more effective than a comprehensive collection. Provide guidance. Short annotations explaining why a resource is relevant and when it should be used help learners make informed choices.';

$string['action_f10'] = 'Hidden Dependencies occur when activities rely on prior knowledge, tools, or content that are not explicitly stated. Learners may fail tasks without understanding why. Identify prerequisites for each activity. If prior knowledge or completion of earlier tasks is required, state this explicitly. Use conditional availability carefully. Make dependencies visible and explain their purpose rather than letting learners discover them through trial and error. Where possible, provide refreshers or links to prerequisite materials. This supports learners with diverse backgrounds.';

$string['action_f11'] = 'Frustrated scrolling results from long pages with little structure, forcing learners to scroll excessively to find relevant information. Break long pages into smaller units. Use headings, accordions, or separate pages to improve readability. Place the most important information at the top. Learners should not need to scroll extensively to understand what to do next. Use visual structure deliberately. White space, headings, and short paragraphs significantly improve orientation.';

$string['action_f12'] = 'Deadline Panic occurs when many deadlines cluster closely together or are poorly communicated. Learners experience stress and may miss submissions. Review your course calendar for deadline clustering. Spread deadlines more evenly across weeks where possible. Communicate deadlines clearly and early. Use consistent naming and ensure deadlines are visible both in activity descriptions and in the course calendar. Consider flexibility. Where appropriate, allow grace periods or multiple attempts to reduce unnecessary stress without compromising academic standards.';
