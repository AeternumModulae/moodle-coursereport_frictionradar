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

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Friction Radar';
$string['plugindescription'] = 'Early signals of learning friction based on aggregated course activity.';
$string['navitem'] = 'Friction Radar';
$string['page_title'] = 'Learning Friction Overview';
$string['page_subtitle'] = 'Aggregated course-level signals (rolling 6-week window).';
$string['overall_score'] = 'Overall Friction Score';
$string['generated_at'] = 'Generated';
$string['window'] = 'Window';
$string['days'] = 'days';
$string['no_data'] = 'Not enough data yet. Values will appear after the nightly cache warm-up.';
$string['warmcache_now'] = 'Generate values now';
$string['warmcache_done'] = 'Cache has been regenerated for this course.';
$string['task_warm_cache'] = 'Friction Radar: queue nightly cache warmers';
$string['task_warm_course'] = 'Friction Radar: warm course cache';

$string['friction_f01'] = 'Cognitive Overload';
$string['friction_f02'] = 'Didactic Bottlenecks';
$string['friction_f03'] = 'Navigational Chaos';
$string['friction_f04'] = 'Overloaded Entry Point';
$string['friction_f05'] = 'Passive Presence Effect';
$string['friction_f06'] = 'Zombie Quizzes';
$string['friction_f07'] = 'Unclear Expectations';
$string['friction_f08'] = 'Structural Disorientation';
$string['friction_f09'] = 'Resource Overload';
$string['friction_f10'] = 'Hidden Prerequisites';
$string['friction_f11'] = 'Frustrated Scrolling';
$string['friction_f12'] = 'Deadline Panic Zone';

$string['explain_f01'] = 'Cognitive Overload describes how much simultaneous mental effort a course requires from learners. The value is calculated based on the number of parallel activities, the density of mandatory resources within course sections, and the average textual complexity of learning materials during the analysis window. High scores indicate that learners are confronted with too much information at the same time.';
$string['explain_f02'] = 'Didactic Pitfalls indicate weaknesses in the instructional design of a course. The score is derived from frequent changes between activity types, missing didactic progression across sections, and inconsistent use of learning formats. High values suggest that the learning path lacks methodological coherence.';
$string['explain_f03'] = 'Navigation Chaos reflects how difficult it is for learners to orient themselves within the course structure. The value is calculated from the depth of the section hierarchy, the number of cross-references between distant sections, and frequent navigation jumps. High scores indicate disorientation and increased cognitive navigation effort.';
$string['explain_f04'] = 'Overambitious Entry measures how demanding the initial phase of a course is. The score is based on the number of mandatory activities and required resources in the early sections, as well as the expected workload shortly after course start. High values indicate that learners face high expectations too early.';
$string['explain_f05'] = 'Participation Theatre identifies apparent participation without meaningful engagement. The value is calculated from high access or login activity combined with low interaction depth, such as minimal forum contributions or repeated passive content views. High scores indicate superficial participation rather than active learning.';
$string['explain_f06'] = 'Zombie Quizzes describe assessment activities that are completed mechanically with little learning effect. The score is derived from repeated quiz attempts with minimal performance improvement, very short completion times, and low variation in responses. High values indicate disengaged or automated quiz behaviour.';
$string['explain_f07'] = 'Unclear Expectations measure how transparent course requirements and assessment criteria are. The value is calculated from missing or late assignment descriptions, unclear grading information, and frequent deadline changes. High scores indicate uncertainty about what is expected from learners.';
$string['explain_f08'] = 'Structure Paradox describes courses that appear formally well structured but are difficult to understand in practice. The score is calculated from deep section nesting combined with low activity density and frequent backtracking in navigation. High values indicate formal structure without functional clarity.';
$string['explain_f09'] = 'Resource Overload measures the extent to which learners are confronted with an excessive amount of learning materials. The value is derived from the number of files, pages, and external links per section, weighted against actual learner interaction with these resources. High scores indicate an oversupply of materials with low effective usage.';
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

$string['privacy:metadata'] = 'The Friction Radar tool stores only aggregated, course-level scores in cache and does not store personal data.';

$string['ui_score'] = 'Score';
$string['ui_formula'] = 'Formula';
$string['ui_inputs'] = 'Calculation inputs';
$string['ui_param'] = 'Parameter';
$string['ui_value'] = 'Value';
$string['ui_notes'] = 'Notes';

$string['action_f01']  = 'A high value for Cognitive Overload indicates that learners are confronted with too many mandatory activities, dense resources, or complex materials at the same time. ';
$string['action_f01'] .= 'To reduce this friction, focus on deliberate reduction and careful sequencing of learning demands. ';
$string['action_f01'] .= 'Start by reviewing the first course sections and identify which activities are truly essential. ';
$string['action_f01'] .= 'If multiple tasks serve a similar purpose, merge them or make some of them optional. ';
$string['action_f01'] .= 'Learners benefit far more from a small number of clearly explained activities than from a long checklist of obligations. ';
$string['action_f01'] .= 'Reduce textual complexity where possible. Long descriptions, nested instructions, and highly abstract explanations increase cognitive load. ';
$string['action_f01'] .= 'Break texts into shorter paragraphs, use headings, and explain expectations in simple and direct language. ';
$string['action_f01'] .= 'Supplement complex content with visuals, examples, or short videos. ';
$string['action_f01'] .= 'Finally, stagger demands over time. Avoid placing many mandatory tasks within the same week or section. ';
$string['action_f01'] .= 'A clear progression from simple to complex allows learners to build confidence before facing higher demands.';

$string['action_f02']  = 'Didactic Pitfalls occur when learning activities are poorly aligned with learning objectives or when instructions are unclear or misleading. ';
$string['action_f02'] .= 'Learners may complete tasks without understanding their purpose, leading to frustration and disengagement. ';
$string['action_f02'] .= 'Begin by checking whether each activity clearly supports a learning outcome. ';
$string['action_f02'] .= 'Explicitly state why an activity exists and what learners should gain from completing it. ';
$string['action_f02'] .= 'If an activity does not clearly contribute to a learning goal, consider revising or removing it. ';
$string['action_f02'] .= 'Improve task descriptions by clarifying required inputs, expected outputs, and assessment criteria. ';
$string['action_f02'] .= 'Avoid implicit assumptions about prior knowledge. ';
$string['action_f02'] .= 'Where possible, include short examples of a successful submission or typical mistakes to avoid. ';
$string['action_f02'] .= 'Consistency is crucial. Use similar formats, terminology, and structures across activities. ';
$string['action_f02'] .= 'When learners recognize patterns, they can focus on learning instead of deciphering instructions.';

$string['action_f03']  = 'Navigation Chaos arises when learners struggle to find materials, activities, or orientation cues within a course. ';
$string['action_f03'] .= 'This often results from inconsistent structure or excessive depth in the course layout. ';
$string['action_f03'] .= 'To reduce this friction, establish a clear and repeatable structure. ';
$string['action_f03'] .= 'Use a consistent naming scheme for sections and activities. ';
$string['action_f03'] .= 'For example, start each week or topic with an overview, followed by materials, and then activities. ';
$string['action_f03'] .= 'Limit unnecessary nesting. Deep hierarchies of folders, pages, and links increase the risk of learners getting lost. ';
$string['action_f03'] .= 'If content must be grouped, explain why and what learners are expected to do next. ';
$string['action_f03'] .= 'Use labels and section summaries to provide orientation. ';
$string['action_f03'] .= 'A short sentence explaining what a section contains and how it fits into the overall course can significantly reduce navigational effort.';

$string['action_f04']  = 'An overambitious entry means that learners are confronted with high demands immediately after the course starts. ';
$string['action_f04'] .= 'This can overwhelm learners before they have established routines or confidence. ';
$string['action_f04'] .= 'Review the first one or two sections of your course. ';
$string['action_f04'] .= 'Count how many mandatory activities and resources are required early on. ';
$string['action_f04'] .= 'Consider postponing complex tasks, assessments, or heavy reading to later sections. ';
$string['action_f04'] .= 'Use the opening phase to orient learners. ';
$string['action_f04'] .= 'Introduce course goals, structure, and expectations gradually. ';
$string['action_f04'] .= 'Low-stakes activities such as short introductions, simple quizzes, or guided walkthroughs help learners acclimate. ';
$string['action_f04'] .= 'Early success matters. ';
$string['action_f04'] .= 'Design initial activities so that most learners can complete them successfully, building confidence and reducing early dropout risk.';

$string['action_f05']  = 'Participation Theatre describes situations where learners are required to participate without meaningful impact or feedback. ';
$string['action_f05'] .= 'Activities exist primarily to demonstrate activity rather than to support learning. ';
$string['action_f05'] .= 'Evaluate whether participation activities lead to reflection, discussion, or knowledge construction. ';
$string['action_f05'] .= 'If forum posts or submissions are required, ensure that they receive feedback or are meaningfully integrated into subsequent activities. ';
$string['action_f05'] .= 'Reduce artificial participation requirements. ';
$string['action_f05'] .= 'Mandatory posts without interaction often lead to superficial contributions. ';
$string['action_f05'] .= 'Instead, encourage fewer but more focused contributions with clear prompts. ';
$string['action_f05'] .= 'Where possible, replace formal participation with authentic tasks. ';
$string['action_f05'] .= 'Collaborative documents, peer feedback, or optional discussion prompts often result in more genuine engagement.';

$string['action_f06']  = 'Zombie Quizzes are assessments that are reused repeatedly without revision and provide little diagnostic or learning value. ';
$string['action_f06'] .= 'Learners may complete them mechanically without reflection. ';
$string['action_f06'] .= 'Review quiz questions for relevance and clarity. ';
$string['action_f06'] .= 'Remove outdated or ambiguous items and ensure that questions align with current course content. ';
$string['action_f06'] .= 'Use feedback strategically. ';
$string['action_f06'] .= 'Immediate, explanatory feedback transforms quizzes into learning tools. ';
$string['action_f06'] .= 'Even short explanations for correct and incorrect answers can significantly improve learning outcomes. ';
$string['action_f06'] .= 'Consider varying quiz formats. ';
$string['action_f06'] .= 'Mixing formative quizzes, practice attempts, and self-assessment questions reduces monotony and increases engagement.';

$string['action_f07']  = 'Unclear Expectations arise when learners are unsure what is required to succeed. ';
$string['action_f07'] .= 'This includes vague grading criteria, missing descriptions, or implicit assumptions. ';
$string['action_f07'] .= 'Ensure that all graded activities include clear descriptions and, where applicable, grading criteria or rubrics. ';
$string['action_f07'] .= 'Learners should understand how their work will be evaluated before submitting it. ';
$string['action_f07'] .= 'Clarify workload expectations. ';
$string['action_f07'] .= 'Indicate approximate time requirements and submission formats to help learners plan their work and reduce anxiety. ';
$string['action_f07'] .= 'Revisit activities from a learner perspective. ';
$string['action_f07'] .= 'If expectations are obvious only to experienced instructors, they are likely unclear to students.';

$string['action_f08']  = 'Structural Disorientation occurs when learners cannot form a clear mental model of the course structure. ';
$string['action_f08'] .= 'This often results from inconsistent organization or frequent structural changes. ';
$string['action_f08'] .= 'Maintain a stable course structure throughout the term. ';
$string['action_f08'] .= 'Avoid moving activities or renaming sections once learners have started working with them. ';
$string['action_f08'] .= 'Use recurring patterns. ';
$string['action_f08'] .= 'For example, each section could follow the same internal order: overview, materials, activities, assessment. ';
$string['action_f08'] .= 'Provide structural signals. ';
$string['action_f08'] .= 'Section summaries, visual separators, and consistent icon usage help learners recognize structure and reduce confusion.';

$string['action_f09']  = 'Resource Overload is caused by an excessive number of files, links, and external resources. ';
$string['action_f09'] .= 'Learners may struggle to identify what is essential. ';
$string['action_f09'] .= 'Audit your resources regularly. ';
$string['action_f09'] .= 'Remove outdated or redundant materials and clearly mark optional resources as such. ';
$string['action_f09'] .= 'Prioritize quality over quantity. ';
$string['action_f09'] .= 'A small number of well-chosen resources is often more effective than a comprehensive collection. ';
$string['action_f09'] .= 'Provide guidance. ';
$string['action_f09'] .= 'Short annotations explaining why a resource is relevant and when it should be used help learners make informed choices.';

$string['action_f10']  = 'Hidden Dependencies occur when activities rely on prior knowledge, tools, or content that are not explicitly stated. ';
$string['action_f10'] .= 'Learners may fail tasks without understanding why. ';
$string['action_f10'] .= 'Identify prerequisites for each activity. ';
$string['action_f10'] .= 'If prior knowledge or completion of earlier tasks is required, state this explicitly. ';
$string['action_f10'] .= 'Use conditional availability carefully. ';
$string['action_f10'] .= 'Make dependencies visible and explain their purpose rather than letting learners discover them through trial and error. ';
$string['action_f10'] .= 'Where possible, provide refreshers or links to prerequisite materials. ';
$string['action_f10'] .= 'This supports learners with diverse backgrounds.';

$string['action_f11']  = 'Frustrated Scrolling results from long pages with little structure, forcing learners to scroll excessively to find relevant information. ';
$string['action_f11'] .= 'Break long pages into smaller units. ';
$string['action_f11'] .= 'Use headings, accordions, or separate pages to improve readability. ';
$string['action_f11'] .= 'Place the most important information at the top. ';
$string['action_f11'] .= 'Learners should not need to scroll extensively to understand what to do next. ';
$string['action_f11'] .= 'Use visual structure deliberately. ';
$string['action_f11'] .= 'White space, headings, and short paragraphs significantly improve orientation.';

$string['action_f12']  = 'Deadline Panic occurs when many deadlines cluster closely together or are poorly communicated. ';
$string['action_f12'] .= 'Learners experience stress and may miss submissions. ';
$string['action_f12'] .= 'Review your course calendar for deadline clustering. ';
$string['action_f12'] .= 'Spread deadlines more evenly across weeks where possible. ';
$string['action_f12'] .= 'Communicate deadlines clearly and early. ';
$string['action_f12'] .= 'Use consistent naming and ensure deadlines are visible both in activity descriptions and in the course calendar. ';
$string['action_f12'] .= 'Consider flexibility. ';
$string['action_f12'] .= 'Where appropriate, allow grace periods or multiple attempts to reduce unnecessary stress without compromising academic standards.';
