# AGENTS.md

Yii2 extension (`dicr/yii2-settings`, PHP >= 8.3). Namespace `proweb\\settings` -> `src/`.

## Commands

- Setup: `composer install`
- Verify (same order as CI): `composer cs-check` -> `composer phpstan` -> `composer rector` -> `composer test`
- Fixes: `composer cs-fix`, `composer rector-fix`
- Single test: `vendor/bin/phpunit --filter TestClassName` or `vendor/bin/phpunit tests/DbSettingsTest.php`

## Environment gotchas (verified locally, Windows)

- `ext-yaml` may be missing on the dev box: `composer install --ignore-platform-req=ext-yaml` and skip `tests/YamlSettingsTest.php` (it runs in CI, where ext-yaml is installed).
- Default `php.ini` has `memory_limit=128M`, which makes PHPStan crash in parallel workers. Run `composer phpstan` with `-d memory_limit=-1` (or bump the limit); CI uses GitHub runners and is unaffected.
- PowerShell splits arguments containing `|`: quote filter expressions, e.g. `--filter="DbSettingsTest"`.

## Architecture notes

- Entry points for app integration: `Module` (registers app components `settingsStore` + `settings`) and `Bootstrap` (module id in bootstrap list).
- `Settings` is a facade delegating to a `SettingsStoreInterface` implementation; store is resolved via `Instance::ensure`, so it accepts class name, config array, or object.
- Store backends live in `src/stores/`: `DbSettingsStore` (default, creates table unless `autoCreateTable = false`), file-based abstract `FileSettingsStore` with `Php`/`Yaml`/`Serialize` variants.
- Known gap (do not "fix" silently): `events/*` and `behaviors/*` are documented in README but never dispatched by `Settings`/stores; see `review-q38-2026-08-21.md` for the full audit.
- Tests build a console `yii\console\Application` in `tests/bootstrap.php` (in-memory sqlite + FileCache); file stores share `tests/test.dat`, cleaned up per test class.

## Conventions

- CS: PSR-12 + PER-CS 2.0 + PHP 8.3 migration rules (`strict_types`, short arrays, alphabetical imports) — keep code passing `cs-check` after edits.
- PHPStan level 8 with generics docblocks required for array parameters/returns; prefer typed properties and explicit generics over `@var` suppression (existing `@phpstan-ignore-next-line` in events is deliberate).
- Repo metadata drift: `composer.json` homepage/support still point at GitLab while the real source is GitHub; version constraint `^2.0.54|^22` — don't "correct" these without confirmation.
