# Changelog

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
