# Changelog

## 0.6.1 - 2026-03-16

- Added a per-course analysis mode setting with structural preview support.
- Improved report prioritization UX and the friction indicator presentation.
- Fixed review findings around cache refresh handling, task deduplication, log reading, and course-setting persistence.
- Corrected the XMLDB key definition for the course settings table.
- Preserved the English brand name in exposed UI locations while keeping the clock center label short.
- Fixed report spacing, content top alignment, empty-state refresh button padding, and the theme-aware navigation icon.
- Bumped the plugin version for the UI/icon update series.
- Refreshed the bundled documentation screenshots in `docs/screenshots/`.
- Added a screenshot section to the README for the current UI.

## 0.5.8 - 2026-02-04

- Fixed duplicate admin settings registration for reports.
- Added CI guard to prevent manual settings registration regressions.
- Updated renderer test to use renderables.

## 0.5.7 - 2026-02-04

- Fixed settings category parent (moved to admin "reports" category).
- PHPCS compliance fix in output renderable ternary formatting.
- Version bump for workflow rerun.

## 0.5.6 - 2026-02-04

- Migrated report output to Mustache template/Output API.
- Added configurable student-like roles for log/attempt analysis.
- Reduced DB load by batching intro/due date fetches and caching table existence checks.
- Excluded dev tooling files from release exports via `.gitattributes`.

## 0.5.5 - 2026-01-29

- Added Moodle GPL headers for CSS and AMD JS files.
- Standardized English strings to sentence case and added capability strings.
- Removed non-English language pack from plugin bundle (AMOS-managed translations).
- Expanded README with plugin directory metadata and repository URLs.
- Renamed repository to `moodle-coursereport_frictionradar`.
