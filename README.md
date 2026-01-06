# Friction Radar (admin_tool_frictionradar)

## English

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
- `tool/frictionradar:view`
- `tool/frictionradar:export` (reserved for future use)

---

### Installation

1. Copy the plugin folder to: admin/tool/frictionradar
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

---

## Deutsch

### Was ist Friction Radar?

Friction Radar ist ein kursbezogenes Analyse-Tool für Moodle, das Kursleitenden und Administratoren hilft, **Lernreibung** sichtbar zu machen.

Lernreibung beschreibt Situationen, in denen Studierende nicht unbedingt scheitern, aber unnötig belastet, verwirrt oder unter Druck geraten. Solche Probleme bleiben in klassischen Abschluss- oder Notenübersichten häufig unsichtbar.

Friction Radar bewertet **keine einzelnen Studierenden**.  
Es arbeitet ausschließlich mit **aggregierten, anonymisierten Kursdaten**.

---

### Was zeigt Friction Radar?

Für jeden Kurs stellt Friction Radar eine **Friction Clock** mit zwölf Indikatoren dar:

1. Kognitive Überlastung  
2. Didaktische Engstellen  
3. Navigationschaos  
4. Überladener Einstieg  
5. Passive Anwesenheit  
6. Zombie-Quizze  
7. Unklare Erwartungen  
8. Strukturelle Desorientierung  
9. Ressourcenüberlastung  
10. Versteckte Voraussetzungen  
11. Frustriertes Scrollen  
12. Deadline-Panik  

Jeder Indikator wird auf einer Skala von **0 bis 100** dargestellt und als Segment der Uhr visualisiert.

In der Mitte zeigt ein **Gesamtwert Lernreibung** das aktuelle Lernklima des Kurses.

Die Darstellung dient der Reflexion und Verbesserung, nicht der Kontrolle oder Bewertung.

---

### Wie werden die Werte berechnet?

- Die Berechnung basiert auf Moodle-Aktivitätslogs und Kursdaten.
- Standardmäßig wird ein **rollierendes Zeitfenster von sechs Wochen** verwendet.
- Es kommen robuste statistische Verfahren (Median, Abweichungen) zum Einsatz.
- Es werden keine personenbezogenen Daten angezeigt oder gespeichert.

Die Berechnungen erfolgen **nicht beim Seitenaufruf**, sondern im Hintergrund.

---

### Caching und Performance

Friction Radar nutzt den Moodle Universal Cache (MUC):

- Alle Kurswerte werden zwischengespeichert.
- Die Benutzeroberfläche bleibt jederzeit performant.
- Ein integrierter **Cache-Warmer** berechnet die Werte automatisch nachts neu.

#### Zeitplan des Cache-Warmers

- Kurse werden einzeln verarbeitet.
- Die Berechnung erfolgt zwischen **02:00 und 05:00 Uhr**.
- Die Systemlast wird gleichmäßig verteilt.
- Tagsüber werden ausschließlich Cache-Werte verwendet.

Sind noch keine Daten verfügbar, weist die Oberfläche dezent darauf hin.

---

### Wer kann Friction Radar sehen?

Friction Radar erscheint in der **Kursadministration**.

Standardmäßig haben Zugriff:
- Kursleitende mit Bearbeitungsrechten
- Manager

Studierende haben keinen Zugriff.

Die Steuerung erfolgt über Moodle-Capabilities:
- `tool/frictionradar:view`
- `tool/frictionradar:export` (für spätere Erweiterungen)

---

### Installation

1. Plugin-Verzeichnis kopieren nach: admin/tool/frictionradar
2. **Website-Administration → Mitteilungen** aufrufen und Installation abschließen.
3. Sicherstellen, dass der Moodle-Cron regelmäßig läuft.

Eine zusätzliche Konfiguration ist nicht erforderlich.

---

### Datenschutz

Friction Radar:
- speichert keine personenbezogenen Daten
- zeigt keine individuellen Lernverläufe
- arbeitet ausschließlich mit aggregierten Kursindikatoren

Das Tool ist für DSGVO-konforme Moodle-Installationen geeignet.

---

### Typische Einsatzszenarien

- Frühes Erkennen von Überlastung oder Unklarheiten
- Reflexion der Kursstruktur und Arbeitsbelastung
- Unterstützung der hochschuldidaktischen Qualitätssicherung
- Vorbereitung interner Evaluationen

Friction Radar ist ein **diagnostisches Instrument**, kein Bewertungssystem.

---

