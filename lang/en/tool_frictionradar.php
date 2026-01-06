<?php
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
$string['action_f01'] = 'Reduce simultaneous demands. Limit the number of required items per section, split long pages into smaller chunks, and add short “summary” blocks at the end of sections. Consider turning optional resources into clearly labelled “Further reading” instead of required steps.';
$string['action_f02'] = 'Make the didactic progression explicit. Add brief learning objectives per section, keep activity types consistent, and introduce new formats with a short explanation. Remove redundant steps and ensure each activity has a clear purpose in the sequence.';
$string['action_f03'] = 'Improve orientation. Flatten overly deep structures, reduce cross-links between distant areas, and add a “You are here” intro at the top of each section. Use consistent naming and provide one stable navigation spine (e.g., weekly sections) instead of multiple competing paths.';
$string['action_f04'] = 'Lower the entry threshold. Move complex tasks out of the first sections, provide a short onboarding unit, and offer a low-stakes first activity. Make expectations explicit early, but distribute workload more evenly across the first weeks.';
$string['action_f05'] = 'Increase meaningful interaction. Replace passive views with small, concrete prompts (e.g., one reflective post, one peer reply). Use completion criteria that require engagement, not only access. Provide examples of “good participation” and reduce empty checklists.';
$string['action_f06'] = 'Improve quiz learning value. Reduce repetitive attempts without feedback, add meaningful feedback per question, and use varied question types. Consider fewer but higher-quality quizzes and encourage reflection (e.g., short explanations for wrong answers).';
$string['action_f07'] = 'Clarify requirements. Provide clear task descriptions, grading criteria, and examples. Keep deadlines stable and communicate changes early. Add a dedicated “Expectations & Assessment” section with a single source of truth for rules and dates.';
$string['action_f08'] = 'Make structure functional, not just formal. Reduce nesting, ensure each section contains a coherent learning unit, and avoid empty container sections. Check whether the structure matches how learners actually navigate and adjust accordingly.';
$string['action_f09'] = 'Curate resources. Remove duplicates, group materials by purpose (core vs. optional), and limit the number of items per section. Provide short annotations (“why this matters”) and consolidate links into one well-structured overview page if needed.';
$string['action_f10'] = 'Make prerequisites visible. Clearly communicate dependencies and access conditions before learners hit a restriction. Add “required before” notes, provide links to prerequisite items, and keep dependency chains short. Consider simplifying conditions where possible.';
$string['action_f11'] = 'Reduce scrolling friction. Break long pages into segments with headings, add a table of contents, and move key actions or links to the top. Prefer multiple short pages over one endless page and ensure important content is not buried.';
$string['action_f12'] = 'De-cluster deadlines. Spread due dates, avoid multiple submissions in the same period, and provide earlier milestones. Use consistent weekly rhythms, add calendar visibility, and clarify submission windows to reduce last-minute pressure.';

$string['privacy:metadata'] = 'The Friction Radar tool stores only aggregated, course-level scores in cache and does not store personal data.';
