# TODO

Tasks for future iterations on yii2-settings.

## Critical (block integration)

### 1. Events are not dispatched

`BeforeSaveEvent` / `AfterSaveEvent` classes exist but are never triggered
via `trigger()`. Required:

- Add `trigger(self::EVENT_BEFORE_SAVE, $event)` in `Settings::set()` and
  `AbstractSettingsModel::save()`
- Add `trigger(self::EVENT_AFTER_SAVE, $event)` after successful save
- In `BeforeSaveEvent`, add cancellation via `$event->cancel`

### 2. Behaviors are not wired in

`CacheBehavior` and `LogBehavior` are standalone classes but the Settings
facade does not use them. Two options:

- **Option A:** Settings delegates events via Event (recommended)
- **Option B:** Settings attaches behaviors via Module config

### 3. Bug in CacheBehavior::clearCache()

Method deletes single key `settings_{module}` but `setToCache()` writes
per-name keys `settings_{module}_{name}`. Invalidation is incomplete.
Fix: `deleteAll("settings_{$module}_*")` or use prefix-based `$cache->delete()`.

### 4. DbSettingsStore::set() lacks transaction

Bulk operations (N delete + insert cycles) are not wrapped in a transaction.
On error, partially written data remains. Fix: wrap in
`Yii::$app->db->transaction()`.

### 5. Security: unserialize with allowed_classes

`SerializeSettingsStore::load()` uses
`unserialize($value, ['allowed_classes' => true])`.
This allows deserializing arbitrary classes. Fix:

- `unserialize($value, ['allowed_classes' => false])` or
- Migrate fully to `igbinary` / `msgpack`

### 6. Migration schema mismatch

- Migration: `id`, `created_at`, `updated_at`
- `DbSettingsStore::createTable()`: `module`, `name`, `value`
  (no id, no timestamps)

Classes do not match. Must be aligned to a single standard.

## Medium priority

### 7. Documentation describes phantom API

- `docs/configuration.md` describes non-existent Module properties
  (`db`, `cache`, `enableCaching`, `cacheDuration`, etc.)
- `docs/examples.md` uses `findOne()` (not AR), non-existent constants
  `EVENT_BEFORE_SAVE`, broken methods (`getAllSettings()`, `$module->set()`)
- Must be rewritten to match real API (Settings facade, SettingsStoreInterface)

### 8. Minimal test coverage

- Each store has only one inherited `testModel()` from AbstractTestCase
- No tests for Settings, Module, Bootstrap, Behaviors, Events
- Minimum: 2-3 tests per class

### 9. composer.json homepage points to GitLab

Real source is GitHub. Fix or remove.

## Low priority

### 10. AfterSaveEvent is never dispatched

Even if events are wired, AfterSaveEvent will not be generated in
`AbstractSettingsModel::save()` -- there is no `trigger()` call there.

### 11. Module has no configuration options

No `storeClass`, no `db`, no `cache` -- everything via `settings` component.
Module registers the component but does not manage it.
