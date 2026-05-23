# Objecting Migration Transition Freeze

Objecting wave 27 freezes the Objecting side of the work after RC2 and opens the next track: backend component migration.

## Locked baseline

- Objecting baseline: `objecting_rc2`
- Active package: `smart-responsor/objecting`
- PHP constraint: `^8.4`
- Symfony constraint: `^8.0`

## Next track

The next track is `backend_component_migration`.

The first pilot components are:

- `Addressing`
- `Taxating`

These components were selected because the workspace audit found local `Object*` clone surfaces there.

## Frozen boundaries

During the pilot migration wave:

- Objecting is frozen as a dependency baseline.
- Exposing is frozen and must not be changed.
- The host application is not the target of the migration.
- Backend components own their Entity, Doctrine migrations, DTO, Form, Controller, Repository, Service, fixtures, tests, and runtime behavior.

## Delivery rules

Use touched-files delivery for backend migrations. Cumulative archives remain backup/reference snapshots only.

Do not perform full repository overwrite or destructive repository cleanup.
