# TODO

Задачи для следующих итераций над yii2-settings.

## Kriticheskie (blokiruyut integratsiyu)

### 1. Events ne vyzyvayutsya
Klassy `BeforeSaveEvent` / `AfterSaveEvent` suschestvuyut, no nigde ne
triggeryatsya cherez `trigger()`. Trebuyetsya:
- Dobavit `trigger(self::EVENT_BEFORE_SAVE, $event)` v `Settings::set()`
  i `AbstractSettingsModel::save()`
- Dobavit `trigger(self::EVENT_AFTER_SAVE, $event)` posle uspeshnogo
  sohraneniya
- V `BeforeSaveEvent` dobavit vozmozhnost otmeny cherez `$event->cancel`

### 2. Behaviors ne podklyucheny
`CacheBehavior` i `LogBehavior` — stabilnye klassy, no Settings facade
ih ne ispolzuet. Dva varianta:
- **Variant A:** Settings delegiruet sobytiya cherez Event (rekomenduetsya)
- **Variant B:** Settings podklyuchaet behaviors cherez Module config

### 3. Bug v CacheBehavior::clearCache()
Metod stiraet odin klyuch `settings_{module}`, no `setToCache()` piset
poimennye klyuchi `settings_{module}_{name}`. Invalidatsiya nepolnaya.
Nuzhno: `deleteAll("settings_{$module}_*")` ili ispolzovat
`$cache->delete()` s prefixom.

### 4. DbSettingsStore::set() bez transaktsii
Bulk-operatsii (N raz delete + insert) ne oberty v transaktsiyu.
V sluchae oshibki chastichno zapisannye dannye ostanutsya.
Nuzhno oborot v `Yii::$app->db->transaction()`.

### 5. Security: unserialize s allowed_classes
`SerializeSettingsStore::load()` ispolzuet
`unserialize($value, ['allowed_classes' => true])`.
Eto pozvolyaet deserializovat lyubye klassy. Nuzhno:
- `unserialize($value, ['allowed_classes' => false])` ili
- Polnostyu peredayti na `igbinary` / `msgpack`

### 6. Nesovpadenie skhemy migratsii i autoCreateTable
- Migratsiya: `id`, `created_at`, `updated_at`
- `DbSettingsStore::createTable()`: `module`, `name`, `value`
  (bez id, bez timestampov)
Klassy ne sovpadayut. Nuzhno privesti k edinomu standartu.

## Sredney vazhnosti

### 7. Dokumentatsiya opisyvaet fantomny API
- `docs/configuration.md` — opisyvaet nesushchestvuyushchie свойства Module
  (`db`, `cache`, `enableCaching`, `cacheDuration` i t.d.)
- `docs/examples.md` — ispolzuet `findOne()` (eto ne AR), nesushchestvuyushchie
  konstanty `EVENT_BEFORE_SAVE`, nekornyye metody Module (`getAllSettings()`,
  `$module->set()`)
- Nuzhno pereschitat pod realnoye API (Settings facade, SettingsStoreInterface)

### 8. Min覆盖测试ov
- Kazhdiy store — tolko odin nasledovannyy `testModel()` iz AbstractTestCase
- Net testov dlya Settings, Module, Bootstrap, Behaviors, Events
- Nuzhno: minimum po 2-3 testa na kazhdy klass

### 9. composer.json homepage ukazyvaet na GitLab
Realnyy istochnik — GitHub. Nuzhno popravit ili udalit.

## Nizkoy vazhnosti

### 10. AfterSaveEvent nigde ne dispatchitsya
Dazhe esli podklyuchit events, AfterSaveEvent ne budet generirovatsya
v AbstractSettingsModel::save() — tam net vyzova trigger().

### 11. Module ne imeet konfiguratsionnykh option
Net `storeClass`, net `db`, net `cache` — vse cherez komponent `settings`.
Module registriruyet komponent, no ne upravlyayet im.
