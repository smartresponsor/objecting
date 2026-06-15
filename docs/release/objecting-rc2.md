# Objecting RC2 baseline

`objecting_rc2` is the active Objecting dependency baseline after the workspace field-pack audit.

## Scope

RC2 includes the RC1 package boundary plus the systemic packs introduced after the audit:

- `object_state`: `object_active`, `object_enabled`, `object_status`
- `object_source`: `object_source`, `object_provider`, `object_external_id`, `object_source_type`
- `object_fingerprint`: `object_hash`, `object_checksum`, `object_algorithm`

RC2 also keeps `object_title` canonical. Business tokens such as `name`, `title`, `description`, `label`, and `displayName` are aliases to `firstTitle`, `middleTitle`, and `lastTitle`.

## Explicit non-scope

RC2 does not introduce these packs:

- `object_id`
- `object_name`
- `object_description`
- `object_priority`
- `object_visibility`

Plain `id` remains owned by backend Doctrine entities and migrations. `priority` and `visibility` stay deferred until their cross-component semantics are separated.

## Ownership boundary

Objecting supplies field packs, embeddables, traits, interfaces, manifests, and adoption contracts. Backend components still own entities, migrations, DTOs, forms, controllers, repositories, services, fixtures, tests, and runtime behavior.

Exposing remains the separate API/OpenAPI contract repository.

## Gates

Use:

```bash
composer dump-autoload
composer test:quality
composer test:rc2
php tools/test/objecting_rc2_check.php
```
