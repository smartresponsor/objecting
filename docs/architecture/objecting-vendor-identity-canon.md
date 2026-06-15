# Objecting vendor identity canon

## Decision

Objecting does not define a tenant entity, tenant identifier, organization owner, or generic object ownership field pack.

In SmartResponsor, `VendorEntity` is the business root. Its PostgreSQL primary key is the canonical cross-system identifier. `VendorSecurityEntity` is a one-to-one technical/security extension that shares the same primary key.

Architecturally the platform may be described as multitenant, but the tenant boundary is already represented by the Vendor identity. A second `tenant_id` would duplicate that identity.

## Objecting responsibility

Objecting owns reusable lifecycle fields:

- `object_created_at`
- `object_created_by`
- `object_modified_at`
- `object_modified_by`
- `object_deleted`
- `object_deleted_at`
- `object_deleted_by`

The `*By` values carry the canonical Vendor identity for the lifecycle operation. Objecting stores that identifier as an opaque scalar because it must not own or depend on `VendorEntity` or `VendorSecurityEntity` Doctrine mappings.

## Ownership boundary

Business ownership remains explicit in consumer components through their normal Vendor relations and primary/foreign keys. Objecting must not add parallel fields such as:

- `tenant_id`
- `object_tenant`
- `object_owner`
- `object_organization`
- generic `object_scope` ownership

## Transaction context

Doctrine transactions use the already-resolved Vendor identity. A transaction boundary does not create another tenant or actor identifier.

## Migration rule

Consumer migrations are backend-owned. When adopting this canon, consumers should:

1. remove duplicated tenant/scope ownership columns only after checking data and constraints;
2. rename `object_updated_at` to `object_modified_at`;
3. rename `object_updated_by` to `object_modified_by`;
4. preserve the Vendor primary identifier stored in lifecycle `*By` fields;
5. update indexes, schema mirrors, serialized contracts, fixtures, and tests atomically.
