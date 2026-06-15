# Objecting sibling pilot migration handoff

`resources/consumer/object-sibling-pilot-migration-handoff.example.yaml` is the final Objecting-side handoff before the first real sibling backend migration wave.

It does not modify sibling repositories by itself. It declares the locked Objecting RC2 baseline and the exact pilot scope for `Addressing` and `Taxating`.

## Locked baseline

- Objecting baseline: `objecting_rc2`
- PHP: `^8.4`
- Symfony packages: `^8.0` only
- Objecting is not modified during the sibling migration wave
- Exposing is not modified during the sibling migration wave

## Pilot components

- `Addressing`
- `Taxating`

These are the pilot components because the workspace audit found local `Object*` clone surfaces in both packages.

## Target field packs

- `object_identity`
- `object_audit`
- `object_title`
- `object_state`
- `object_source`
- `object_fingerprint`

`id` remains a backend-owned Doctrine primary key. `name`, `title`, `description`, `shortDescription`, `label`, and `displayName` remain aliases of `object_title`.

`priority` and `visibility` remain deferred until their backend semantics are separated.

## Delivery boundary

- touched-files patches only
- cumulative snapshots are backup/reference only
- no full repository overwrite
- no destructive repository cleanup
- no `/src/Domain/`
- no Port/Adapter pattern

## Gate

Run:

```bash
composer test:sibling-pilot-migration-handoff
composer test:quality
```
