# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Added CHANGELOG.md file
- Added LICENSE file (GPL-3.0-or-later)
- Added .editorconfig for consistent coding styles
- Added .gitattributes for proper line endings
- Added .styleci.yml for code style checking
- Added .github/dependabot.yml for dependency updates
- Added .github/linters/ for GitHub Actions linting
- Added GitHub Actions workflows for CI/CD
- Added infection.json5 for mutation testing
- Added ecs.php for Easy Coding Standard
- Added composer-require-checker.json for dependency checking
- Added docs/ directory with comprehensive documentation
- Updated composer.json with proper scripts and configuration

### Changed

- Changed namespace from `dicr\settings` to `proweb\settings`
- Updated composer.json with new package name and configuration
- Updated README.md with new documentation links
- Updated UPGRADE.md with migration instructions
- Updated AGENTS.md with agent-specific instructions

### Deprecated

- None

### Removed

- None

### Fixed

- None

### Security

- Added security scanning via GitHub Actions

## [1.0.0] - 2024-01-01

### Added

- Initial release of yii2-settings extension
- Settings storage with multiple backends (Database, File, PHP, Serialize, YAML)
- Bootstrap class for automatic module registration
- Cache and Log behaviors
- Abstract Settings Model
- Events for before/after save
- Database migration for settings table
