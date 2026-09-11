# Objecting Doctrine mapping contract

Objecting provides Doctrine embeddables and embeddable traits for reusable system field packs. Logical pack/type names remain Objecting-prefixed, while physical database columns are entity-native and unprefixed. Backend components remain the runtime owners of their entities and migrations.

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
Objecting embedded traits, entity-native physical columns, columnPrefix=false,
and backend migration ownership.
```

## Column prefix policy

Objecting embeddables define canonical entity-native physical column names such as `id`, `uuid`, `slug`, `created_at`, and `active`. Backend traits use `columnPrefix: false` so Doctrine does not leak embeddable/property ownership into storage names. Logical names such as `object_identity` and `ObjectIdentityEmbeddableTrait` remain namespaced in code only.

## Baseline packs

Every backend object consumer must include:

```text
object_identity
object_audit
object_title
```

Additional packs remain opt-in: publication, soft-delete, version, locale, token, restriction, lock, workflow, code, and config.
