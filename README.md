# Friction Radar (coursereport_frictionradar)

[![CI](https://github.com/AeternumModulae/moodle-coursereport_frictionradar/actions/workflows/ci.yml/badge.svg)](https://github.com/AeternumModulae/moodle-coursereport_frictionradar/actions/workflows/ci.yml)

## Plugin directory description

**Short description:** Course report that visualizes aggregated learning friction signals as a 12-segment friction clock.

**Full description:** Friction Radar is a Moodle course report that surfaces early, aggregated signals of learning friction. It analyses activity logs and standard course data over a rolling six-week window and visualizes twelve friction indicators (plus an overall score) in a friction clock designed for reflection and improvement, not learner evaluation. The report is strictly course-level and anonymized; it does not store or display individual learner data and runs heavy calculations offline via scheduled cache warmers for performance.

## Learning friction display

### What is Friction Radar?

Friction Radar is a course-level analysis tool for Moodle that helps instructors and administrators identify **learning friction** within a course.

Learning friction describes situations where students are not necessarily failing, but where learning becomes unnecessarily difficult, confusing, or stressful. These issues often remain invisible in traditional completion or grading reports.

Friction Radar does **not** evaluate individual students.  
It works exclusively with **aggregated, anonymized course-level data**.

---

### What does Friction Radar show?

For each course, Friction Radar provides a **Friction Clock** consisting of twelve indicators:

1. Cognitive Overload  
2. Didactic Bottlenecks  
3. Navigational Chaos  
4. Overloaded Entry Point  
5. Passive Presence Effect  
6. Zombie Quizzes  
7. Unclear Expectations  
8. Structural Disorientation  
9. Resource Overload  
10. Hidden Prerequisites  
11. Frustrated Scrolling  
12. Deadline Panic Zone  

Each indicator is scored on a scale from **0 to 100** and visualized as a segment of the clock.

At the center, an **Overall Friction Score** summarizes the current learning climate of the course.

The visualization is designed to support reflection and improvement, not evaluation or control.

---

### How are the values calculated?

- All values are based on Moodle activity logs and standard course data.
- The analysis uses a **rolling six-week observation window** by default.
- Values are calculated using robust statistical methods (medians and deviations), not raw counts.
- No individual learner data is displayed or stored.

The calculations are performed **offline**, not during page load.

---

### Caching and Performance

Friction Radar uses Moodle’s Universal Cache (MUC):

- All course scores are cached.
- The user interface never performs heavy calculations.
- A built-in **cache warmer** recalculates course values automatically during the night.

#### Cache Warmer Schedule

- Courses are processed individually.
- Calculations are distributed between **02:00 and 05:00** server time.
- Load is evenly spread to avoid performance peaks.
- Cached values are reused during the day.

If no cached data is available yet, the UI will display a short notice indicating that values are being prepared.

---

### Who can see Friction Radar?

Friction Radar appears in the **Course administration** menu.

By default, access is granted to:
- Editing teachers
- Managers

Students never see Friction Radar.

Access is controlled via Moodle capabilities:
- `coursereport/frictionradar:view`
- `coursereport/frictionradar:export` (reserved for future use)

---

### Installation

Requires Moodle 4.5 or later.
CI currently runs on PHP 8.2 only (other PHP versions are not covered by CI).

1. Copy the plugin folder to: course/report/frictionradar
2. Visit **Site administration → Notifications** to complete installation.
3. Ensure Moodle cron is running regularly.

No additional configuration is required.

---

### Source control, issues, documentation

- Repository: https://github.com/AeternumModulae/moodle-coursereport_frictionradar
- Issue tracker: https://github.com/AeternumModulae/moodle-coursereport_frictionradar/issues
- Documentation: https://github.com/AeternumModulae/moodle-coursereport_frictionradar?tab=readme-ov-file#friction-radar-coursereport_frictionradar

---

### Dependencies

- No external services or third-party PHP libraries.
- Uses Moodle core APIs (MUC, logstore_standard_log, course module APIs).

---

### Build and packaging

- No build step is required for installation; the AMD build output is committed.
- The `package.json` and `package-lock.json` are only for local linting of AMD source.

---

### License

This plugin is licensed under the **GNU GPL v3 or later**. See `LICENSE` for the full text.

---

### Third-party components

No third-party PHP/JS/CSS libraries are bundled with the plugin. If any external code or assets are added in the future, we document them in `thirdpartylibs.xml` and include the relevant license texts to remain Moodle-compliant.

---

### Tests

- PHPUnit tests in `tests/` (renderer, cache, calculator, scheduled tasks).
- CI runs PHPUnit against Moodle 4.5 (MOODLE_405_STABLE) on **MySQL** and **PostgreSQL** via `.github/workflows/ci.yml`.
- PHPCS/PHPMD and ESLint (amd/src) also run in CI.

---

### Privacy and Data Protection

Friction Radar:
- does not store personal learner data
- does not display individual behavior
- only uses aggregated course-level indicators

The plugin is suitable for GDPR-compliant environments.

---

### Typical Use Cases

- Identifying overload or confusion early in a semester
- Reflecting on course structure and workload
- Supporting didactic quality assurance
- Preparing course reviews or internal evaluations

Friction Radar is intended as a **diagnostic instrument**, not as a performance ranking tool.
