# Objecting Backend Clone Cleanup Contract

Objecting wave 23 defines the Objecting-side contract for the first backend clone-cleanup pilot.

The workspace audit found local `Object*` clone surfaces in `Addressing` and `Taxating`. This contract does not modify those sibling repositories. It only publishes the checklist and machine-readable surface that a later backend migration wave should follow.

## Scope

The cleanup contract covers local pre-Objecting clone files such as:

- `src/EntityTrait/ObjectTrait.php`
- `src/EntityTrait/ObjectAuditTrait.php`
- `src/EntityInterface/Common/ObjectTitleInterface.php`
- component-specific `*ObjectEntityTrait.php` files

## Replacement direction

Local clone surfaces should be replaced by explicit Objecting field packs:

- `object_identity`
- `object_audit`
- `object_title`
- `object_publication`
- `object_soft_delete`
- `object_version`
- `object_state`
- `object_source`

The exact set is component-specific and must be declared in the backend `resources/objecting/<BusinessStem>/object-field-packs.yaml` file.

## Guardrails

- Backend components keep Entity, Doctrine migration, DTO, Form, Controller, Repository, Service, fixture, test, and runtime ownership.
- Objecting owns reusable system fields only.
- Cumulative snapshots are backups/reference bases, not destructive apply payloads.
- Apply scripts must use touched-file overlays and explicit clone-file deletes only.
- Whole-repository cleanup scripts are forbidden.
