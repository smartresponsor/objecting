# Objecting Doctrine mapping contract

Objecting provides Doctrine embeddables and embeddable traits for reusable system field packs. Logical pack/type names remain Objecting-prefixed, while physical database columns are flat entity-native names without an Objecting ownership prefix. Backend components remain the runtime owners of their entities and migrations.

## Boundary

- Objecting owns reusable field-pack classes such as `ObjectIdentityEmbeddable`, `ObjectAuditEmbeddable`, and `ObjectTitleEmbeddable`.
- Backend components own `src/Entity/*`, Doctrine table naming, migrations, repositories, DTOs, forms, controllers, fixtures, tests, and runtime behavior.
- Exposing may mirror the schema for API/contract visibility, but it does not own Doctrine migrations.

## Required backend declaration

A backend component should place a mapping contract near its Objecting adoption files, for example:

```text
resources/objecting/Page/object-doctrine-mapping.yaml
```

The contract must declare:

```text
component, business_stem, namespace, entity class, table name,
field-pack contract path, required field packs, Objecting embeddables,
Objecting embedded traits, canonical entity-native physical columns, columnPrefix=false,
and backend migration ownership.
```

## Column prefix policy

Objecting embeddables define canonical physical system column names such as `uuid`, `slug`, `created_at`, and `active`. Backend traits use `columnPrefix: false` so Doctrine preserves those explicit entity-native names instead of adding the embedded-property name as a prefix. Consumer-owned primary keys such as `id` remain outside Objecting.

## Identity uniqueness policy

When `App\\Objecting\\ObjectBundle` is active, Objecting registers a Doctrine `loadClassMetadata` listener for entities embedding `ObjectIdentityEmbeddable`. The listener adds deterministic table-level unique constraints named `uniq_<table>_uuid` and `uniq_<table>_slug` and fails if the canonical `uuid` or `slug` column is missing.

Consumers must not redeclare those invariants with column-level `unique: true` or hash-derived unique-constraint names. Consumer migrations remain consumer-owned and must converge existing database constraints to the deterministic Objecting metadata contract.

## Baseline packs

Every backend object consumer must include:

```text
object_identity
object_audit
object_title
```

Additional packs remain opt-in: publication, soft-delete, version, locale, token, restriction, lock, workflow, code, and config.
