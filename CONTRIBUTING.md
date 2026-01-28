# Contributing to Friction Radar

Thank you for your interest in contributing to **Friction Radar**.  
This project is a Moodle course report plugin focused on aggregated, anonymized learning analytics. Contributions are welcome, provided they respect the project’s scope, quality standards, and privacy principles.

## Scope and Principles

Before contributing, please note:

- This plugin operates **exclusively on course-level, aggregated data**
- **No individual learner data** must be introduced, stored, or displayed
- The tool is intended for **reflection and improvement**, not evaluation or surveillance
- Changes must remain compatible with Moodle core APIs and GPLv3 licensing

If a proposed change conflicts with these principles, it will not be accepted.

## How to Contribute

You can contribute in several ways:

- Reporting bugs
- Suggesting improvements or new indicators
- Submitting pull requests
- Improving documentation or tests

Please use GitHub Issues and Pull Requests for all contributions.

## Reporting Issues

When opening an issue, please include:

- Moodle version
- PHP version
- Database type (MySQL / PostgreSQL)
- Clear steps to reproduce the problem
- Expected vs. actual behavior

If possible, include screenshots or logs.  
Do **not** include personal or learner-related data.

## Pull Requests

Pull requests are welcome. Please ensure that:

- The PR addresses a single, clearly defined concern
- Code follows Moodle coding guidelines
- Existing functionality is not broken
- New logic is covered by tests where applicable
- PHPDoc and inline comments are updated if behavior changes

Before submitting, make sure all CI checks pass.

## Development Setup

- Supported Moodle version: **Moodle 4.5 or later**
- PHP version covered by CI: **PHP 8.2**
- No external PHP libraries are used
- JavaScript follows the AMD module structure used by Moodle

Local linting and tests should be run before submitting a PR.

## Code Style and Quality

- Follow Moodle core coding standards
- Prefer readability over cleverness
- Use descriptive names and explicit logic
- Avoid premature optimization
- Keep calculations deterministic and explainable

## Licensing

By contributing to this repository, you agree that your contributions will be licensed under the **GNU General Public License v3 or later**, consistent with the rest of the project.

## Questions and Discussion

For questions, clarifications, or design discussions, please use:

- GitHub Issues
- GitHub Discussions (if enabled)

Thank you for helping improve Friction Radar.
