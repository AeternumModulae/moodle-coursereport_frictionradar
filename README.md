# Friction Radar (coursereport_frictionradar)

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

1. Copy the plugin folder to: course/report/frictionradar
2. Visit **Site administration → Notifications** to complete installation.
3. Ensure Moodle cron is running regularly.

No additional configuration is required.

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
