# Objecting current-snapshot tenant/lifecycle audit

## Source boundary

- Source archive: `Objecting(5).zip`
- SHA-256: `5178ab4c629bc79f0874feede33156e3d1e64d18ee3c97aa3a1c6475b49fd316`
- This audit and patch use only that archive as the current Objecting snapshot.

## Canonical decision applied

- `VendorEntity` is the business root.
- `VendorEntity.id` is the PostgreSQL primary key and canonical cross-system identity.
- `VendorSecurityEntity` is a one-to-one technical/security extension sharing the same primary key.
- SmartResponsor may be described architecturally as multitenant, but Objecting must not materialize a second tenant identity.
- Lifecycle fields use `created`, `modified`, and `deleted` vocabulary.
- `createdBy`, `modifiedBy`, and `deletedBy` carry the canonical Vendor identity as an opaque scalar in Objecting.

## Confirmed drifts

### 1. Generic tenant/scope field pack

`ObjectScopeEmbeddable` mixed four different concepts:

- `object_scope`
- `object_tenant`
- `object_organization`
- `object_owner`

This pack duplicated Vendor ownership, leaked business semantics into Objecting, and created an additional tenant/context coordinate. The complete `object_scope` field pack was removed from source, manifests, profiles, release declarations, examples, tests, and quality checks.

### 2. Double lifecycle vocabulary

The audit pack stored `object_updated_at` / `object_updated_by`, while exposing `modified` aliases. This represented one lifecycle fact through two competing vocabularies.

Canonical storage and API are now:

- `object_created_at`
- `object_created_by`
- `object_modified_at`
- `object_modified_by`
- `object_deleted`
- `object_deleted_at`
- `object_deleted_by`

The compatibility alias layer was removed rather than preserved as permanent dual terminology.

### 3. Missing imports

The current snapshot contained unresolved namespace drift:

- `ObjectEmbeddableFactory` referenced embeddables from the wrong namespace because imports were missing.
- the active field-pack registry referenced state/source/fingerprint embeddables without imports.

The imports were added.

### 4. No regression guard

A new `test:lifecycle-identity-canon` gate rejects reintroduction of:

- `tenant_id`
- `tenantId`
- `object_tenant`
- the deleted ObjectScope surface
- `object_updated_at` / `object_updated_by`
- updated-style audit accessors

## Files changed

- 59 touched paths
- 3 added
- 6 deleted
- 50 modified

Deleted canonical drift surfaces:

- `src/Embeddable/ObjectScopeEmbeddable.php`
- `src/EntityTrait/Embeddable/ObjectScopeEmbeddableTrait.php`
- `src/EntityInterface/ObjectScopedInterface.php`
- `resources/field-pack/object-scope.yaml`
- `docs/field-pack/object-scope.md`
- `tests/Unit/ObjectLifecycleAliasCompatibilityTest.php`

## Validation

The follow-up quality cleanup was applied to the first-wave full snapshot. The previously recorded six standalone failures are now resolved.

Current validation passed:

- PHP syntax lint across 201 PHP files in `src`, `tests`, and `tools`;
- JSON/YAML parse across the package;
- all 26 standalone `tools/test/*.php` quality gates;
- lifecycle and field-pack runtime smoke tests with 16 canonical packs;
- full internal `App\Objecting` autoload surface except the two classes that require unavailable external Symfony dependencies;
- explicit internal type-resolution gate;
- no active `nameEntity`, `App\Objecting\Service`, `App\Objecting\ServiceInterface`, `src/Service`, or `src/ServiceInterface` drift.

The follow-up details are recorded in `OBJECTING_QUALITY_CLEANUP_AUDIT.md`.

`composer test`, PHPUnit, and PHPStan remain unavailable in this sandbox because the archive contains no `vendor/` and Composer is not installed. Their source files lint successfully, and all package-owned standalone gates pass.

## Deliberately not changed

`object_created_by`, `object_modified_by`, and `object_deleted_by` remain `string(190)` opaque identifiers. The Objecting snapshot does not contain the canonical `VendorEntity` mapping, so changing these columns to `integer`, `bigint`, UUID, or a Doctrine association would be an unsupported assumption. That type decision must be made against the current Vendoring/Vendor entity snapshot before database migrations are generated.

The large workspace field-pack audit retains historical occurrences of `tenant`, `owner`, `organization`, `scope`, and `updatedAt` as evidence for later sibling-component cleanup. A notice now marks those occurrences as observations, not Objecting canon.

## Consumer migration impact

Consumer-owned migrations will need to:

1. remove adopted `object_scope`, `object_tenant`, `object_organization`, and `object_owner` columns where they came from Objecting;
2. rename `object_updated_at` to `object_modified_at`;
3. rename `object_updated_by` to `object_modified_by`;
4. update indexes, schema mirrors, fixtures, serialized contracts, and tests in the same migration wave;
5. preserve the existing canonical Vendor identifier values.
