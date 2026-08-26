# Configuration Reference

## Module

Properties:

- `settingsClass` (string, default `Settings::class`) -- facade class
- `storeClass` (string, default `DbSettingsStore::class`) -- store class
- `storeConfig` (array, default `[]`) -- store constructor config

Module registers `settingsStore` + `settings` components in
`registerComponents()`.

## SettingsStoreInterface

All stores implement 3 methods:

```php
get(string $module, ?string $name = null, mixed $default = null): mixed;
set(string $module, array|string $name, mixed $value = null): static;
delete(string $module, ?string $name = null): static;
```

## DbSettingsStore

Properties:

- `db` (Connection|string, default `'db'`) -- DB connection
- `tableName` (string, default `'{{%settings}}'`) -- table name
- `format` (string, default `'json'`) -- value encoding: `string`, `json`, `serialize`
- `autoCreateTable` (bool, default `true`) -- create table on init if missing

Schema: `module VARCHAR NOT NULL`, `name VARCHAR NOT NULL`,
`value TEXT`. Unique index on `(module, name)`.

## FileSettingsStore (abstract)

Properties:

- `filename` (string, required) -- file path (supports Yii aliases)

## PhpSettingsStore

Extends `FileSettingsStore`. Stores settings as PHP array return.
Inherits `filename` property.

## SerializeSettingsStore

Extends `FileSettingsStore`. Stores settings via `serialize()`.
Inherits `filename` property.

## YamlSettingsStore

Extends `FileSettingsStore`. Stores settings as YAML (requires ext-yaml).
Inherits `filename` property.
