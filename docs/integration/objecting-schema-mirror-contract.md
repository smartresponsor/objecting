# Objecting schema mirror contract

Objecting publishes a schema mirror contract for backend components that consume `object_*` system field packs.

## Boundary

- Backend components own Doctrine entities, migrations, repositories, fixtures, tests, and runtime behavior.
- Objecting owns reusable `object_*` system columns through field packs, embeddables, traits, and interfaces.
- Exposing owns API-visible schema mirrors such as `contract/component/Paging/Page/page.db-schema.yaml`.

The schema mirror is informational and contract-oriented. It does not replace Doctrine migrations.

## Backend placement

A backend component can keep a local mirror declaration near its Objecting adoption files:

```text
resources/schema/Page/object-schema-mirror.yaml
```

The local declaration should point to:

```text
resources/objecting/Page/object-field-packs.yaml
resources/objecting/Page/object-doctrine-mapping.yaml
contract/component/Paging/Page/page.db-schema.yaml
```

## Required baseline

Every backend object consumer must include:

```text
object_identity
object_audit
object_title
```

Backend business columns must not use the `object_` prefix. That prefix belongs to Objecting system field packs.
