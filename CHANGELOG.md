# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- CI/CD: build, quality, static, mutation, security workflows
- CI/CD: dependabot.yml, .editorconfig, .gitattributes
- CI/CD: infection.json5 (mutation testing), ecs.php (ECS)
- CI/CD: composer-require-checker, .gitleaks.toml
- CHANGELOG.md, LICENSE (GPL-3.0-or-later), TODO.md
- AGENTS.md (contributor guide)
- docs/ directory (installation, configuration, examples, testing, development)

### Changed

- Namespace `dicr\settings` -> `proweb\settings` (all 24 files)
- Test namespace `dicr\tests` -> `proweb\tests` (6 test files)
- CI: Windows build extensions, persist-credentials, YAML brackets
- CI: editorconfig-checker excludes markdown files
- Pinned all GitHub Actions to commit SHAs
- README badge URLs refactored to reference-style
- `.gitattributes`: `* text=auto eol=lf`

### Deprecated

- None

### Removed

- UPGRADE.md (context preserved in CHANGELOG and AGENTS.md)
- review-q38-2026-08-21.md (findings moved to TODO.md)

### Fixed

- Double backslashes in all 20 PHP files
- Markdown lint errors (MD013, MD029, MD040, MD031)
- Prettier formatting (blank lines around markdownlint comments)
- .gitleaks.toml indentation
- Ordered list prefixes in docs/development.md

### Security

- CI: Zizmor disabled (upstream shivammathur/setup-php vulnerability,
  GHSA-5wxr-w449-57cm, GHSA-pqwm-q9pv-ph8r)
- Known: `SerializeSettingsStore` uses `unserialize()` with
  `allowed_classes = true` (see TODO.md)

## [1.0.0] - 2024-01-01

### Added

- Initial release of yii2-settings extension
- Settings storage with multiple backends (Database, File, PHP, Serialize, YAML)
- Bootstrap class for automatic module registration
- Cache and Log behaviors (not wired into Settings facade)
- Abstract Settings Model
- Events for before/after save (not dispatched)
- Database migration for settings table
