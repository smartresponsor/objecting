# Objecting backend migration command packet

`resources/consumer/object-backend-migration-command.example.yaml` is the Objecting-side packet for the first real sibling backend migration wave.

It does not change sibling repositories by itself. It gives Codex and humans a stable command surface for migrating backend components after Objecting RC1 plus systemic field-pack expansion.

## Boundary

- Objecting is the dependency baseline.
- Backend components own Entity, Doctrine migrations, DTO, Form, Controller, Repository, Service, fixtures, tests, and runtime behavior.
- Exposing is not modified in this backend migration wave.
- Delivery remains touched-files only.
- Cumulative snapshots are backups/reference bases, not destructive overwrite payloads.

## First pilot

The first pilot components are `Addressing` and `Taxating`, because the workspace audit found local `Object*` clone surfaces there.

## Field-pack mapping

Baseline packs:

- `object_identity`
- `object_audit`
- `object_title`

Systemic packs:

- `object_state`
- `object_source`
- `object_fingerprint`

`id` remains a backend-owned Doctrine primary key. `name`, `title`, `description`, `shortDescription`, `label`, and `displayName` are aliases of `object_title` and must not become duplicate database columns when the backend adopts `object_title`.

`priority` and `visibility` remain deferred until a focused semantics decision.

## Gate

Run:

```bash
composer test:backend-migration-command
composer test:quality
```
