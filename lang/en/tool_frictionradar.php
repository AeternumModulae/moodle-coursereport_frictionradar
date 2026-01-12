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
$string['action_f01'] = <<<TXT
A high value for Cognitive Overload indicates that learners are confronted with too many mandatory activities, dense resources, or complex materials at the same time. To reduce this friction, focus on deliberate reduction and sequencing.

Start by reviewing the first course sections and identify which activities are truly essential. If multiple tasks serve a similar purpose, merge them or make some optional. Learners benefit more from a small number of clearly explained activities than from a long checklist of obligations.

Reduce textual complexity where possible. Long descriptions, nested instructions, and highly abstract explanations increase cognitive load. Break texts into shorter paragraphs, use headings, and explain expectations in simple language. Supplement complex texts with visuals, examples, or short videos.

Finally, stagger demands over time. Avoid placing many mandatory tasks within the same week or section. A clear progression from simple to complex allows learners to build confidence before facing higher demands.
TXT;
$string['action_f02'] = <<<TXT
Didactic Pitfalls occur when learning activities are poorly aligned with objectives or when instructions are unclear or misleading. Learners may complete tasks without understanding their purpose, leading to frustration and disengagement.

Begin by checking whether each activity clearly supports a learning outcome. Explicitly state why an activity exists and what learners should gain from it. If an activity does not clearly contribute to a goal, consider revising or removing it.

Improve task descriptions by clarifying inputs, expected outputs, and assessment criteria. Avoid implicit assumptions about prior knowledge. Where possible, include short examples of a successful submission or common mistakes to avoid.

Consistency is crucial. Use similar formats, terminology, and structures across activities. When learners recognize patterns, they can focus on learning instead of deciphering instructions.
TXT;
$string['action_f03'] = <<<TXT
Navigation Chaos arises when learners struggle to find materials, activities, or orientation cues within the course. This often results from inconsistent structure or excessive depth in the course layout.

To reduce this friction, establish a clear and repeatable structure. Use a consistent naming scheme for sections and activities. For example, start each week or topic with an overview, followed by materials, then activities.

Limit unnecessary nesting. Deep hierarchies of folders, pages, and links increase the risk of learners getting lost. If content must be grouped, explain why and what learners are expected to do next.

Use labels and section summaries to provide orientation. A short sentence explaining what a section contains and how it fits into the overall course can significantly reduce navigational effort.
TXT;
$string['action_f04'] = <<<TXT
An overambitious entry means that learners are confronted with high demands immediately after course start. This can overwhelm learners before they have established routines or confidence.

Review the first one or two sections of your course. Count how many mandatory activities and resources are required early on. Consider postponing complex tasks, assessments, or heavy reading to later sections.

Use the opening phase to orient learners. Introduce the course goals, structure, and expectations gradually. Low-stakes activities such as short introductions, simple quizzes, or guided walkthroughs help learners acclimate.

Early success matters. Design initial activities so that most learners can complete them successfully. This builds confidence and reduces early dropout risk.
TXT;
$string['action_f05'] = <<<TXT
Participation Theatre describes situations where learners are required to participate without meaningful impact or feedback. Activities exist primarily to demonstrate activity, not learning.

Evaluate whether participation activities lead to reflection, discussion, or knowledge construction. If forum posts or submissions are required, ensure that they receive feedback or are meaningfully integrated into subsequent activities.

Reduce artificial participation requirements. Mandatory posts without interaction often lead to superficial contributions. Instead, encourage fewer but more focused contributions with clear prompts.

Where possible, replace formal participation with authentic tasks. For example, collaborative documents, peer feedback, or optional discussion prompts often result in more genuine engagement.
TXT;
$string['action_f06'] = <<<TXT
Zombie Quizzes are assessments that are reused repeatedly without revision and provide little diagnostic or learning value. Learners may complete them mechanically without reflection.

Review quiz questions for relevance and clarity. Remove outdated or ambiguous items and ensure that questions align with current course content.

Use feedback strategically. Immediate, explanatory feedback transforms quizzes into learning tools. Even short explanations for correct and incorrect answers can significantly improve learning outcomes.

Consider varying quiz formats. Mixing formative quizzes, practice attempts, and self-assessment questions reduces monotony and increases engagement.
TXT;
$string['action_f07'] = <<<TXT
Unclear Expectations arise when learners are unsure what is required to succeed. This includes vague grading criteria, missing descriptions, or implicit assumptions.

Ensure that all graded activities include clear descriptions and, where applicable, grading criteria or rubrics. Learners should understand how their work will be evaluated before submitting it.

Clarify workload expectations. Indicate approximate time requirements and submission formats. This helps learners plan and reduces anxiety.

Revisit activities from a learner perspective. If expectations are obvious only to experienced instructors, they are likely unclear to students.
TXT;
$string['action_f08'] = <<<TXT
Structural Disorientation occurs when learners cannot form a mental model of the course structure. This often results from inconsistent organization or frequent structural changes.

Maintain a stable course structure throughout the term. Avoid moving activities or renaming sections once learners have started working with them.

Use recurring patterns. For example, each section could follow the same internal order: overview, materials, activities, assessment.

Provide structural signals. Section summaries, visual separators, and consistent icon usage help learners recognize structure and reduce confusion.
TXT;
$string['action_f09'] = <<<TXT
Resource Overload is caused by an excessive number of files, links, and external resources. Learners may struggle to identify what is essential.

Audit your resources regularly. Remove outdated or redundant materials and clearly mark optional resources as such.

Prioritize quality over quantity. A small number of well-chosen resources is often more effective than a comprehensive collection.

Provide guidance. Short annotations explaining why a resource is relevant and when it should be used help learners make informed choices.
TXT;
$string['action_f10'] = <<<TXT
Hidden Dependencies occur when activities rely on prior knowledge, tools, or content that are not explicitly stated. Learners may fail tasks without understanding why.

Identify prerequisites for each activity. If prior knowledge or completion of earlier tasks is required, state this explicitly.

Use conditional availability carefully. Make dependencies visible and explain their purpose rather than letting learners discover them through trial and error.

Where possible, provide refreshers or links to prerequisite materials. This supports learners with diverse backgrounds.
TXT;
$string['action_f11'] = <<<TXT
Frustrated Scrolling results from long pages with little structure, forcing learners to scroll excessively to find relevant information.

Break long pages into smaller units. Use headings, accordions, or separate pages to improve readability.

Place the most important information at the top. Learners should not need to scroll extensively to understand what to do next.

Use visual structure deliberately. White space, headings, and short paragraphs significantly improve orientation.
TXT;
$string['action_f12'] = <<<TXT
Deadline Panic occurs when many deadlines cluster closely together or are poorly communicated. Learners experience stress and may miss submissions.

Review your course calendar for deadline clustering. Spread deadlines more evenly across weeks where possible.

Communicate deadlines clearly and early. Use consistent naming and ensure deadlines are visible both in the activity description and the course calendar.

Consider flexibility. Where appropriate, allow grace periods or multiple attempts to reduce unnecessary stress without compromising academic standards.
TXT;

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

