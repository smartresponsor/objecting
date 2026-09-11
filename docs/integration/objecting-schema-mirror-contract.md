# Objecting schema mirror contract

Objecting publishes a schema mirror contract for backend components that consume Objecting system field packs. Logical pack identifiers remain `object_*`, but physical database columns are flat, entity-native names.

## Boundary

- Backend components own Doctrine entities, migrations, repositories, fixtures, tests, and runtime behavior.
- Objecting owns reusable system-field semantics and mappings through field packs, embeddables, traits, and interfaces; physical columns use entity-native names without an Objecting ownership prefix.
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

Physical database columns must not use the `object_` prefix, including Objecting-provided system fields. The prefix belongs to logical field-pack identifiers and PHP ownership surfaces, not to table storage. When a domain field has semantics distinct from a generic Objecting field, use a semantic qualifier such as `inventory_status`, `tax_code`, or `price_source`.
