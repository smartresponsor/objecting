# SmartResponsor Objecting System-Field Canon

## Purpose

This file is the persistent Codex contract for ecosystem-wide migration to the canonical Objecting system fields.

When the task asks to find applications or components and normalize their system fields, treat this file as authoritative. Apply it to every repository in the selected SmartResponsor workspace, subject to more specific nested `AGENTS.md` instructions.

Do not merely rename symbols. Produce executable Doctrine mappings, migrations, runtime calls, queries, serializers, forms, fixtures, and tests that use the canonical Objecting field packs.

## Repository and snapshot rules

- Use only the latest repository snapshot explicitly identified by the user as the current snapshot.
- Do not reuse code, patches, assumptions, or deleted files from an older snapshot unless archive hashes are identical.
- Before editing, record the current archive or Git tree hash when available.
- Prefer touched-files patches over full repository replacement.
- Do not introduce `/src/Domain/`, Ports and Adapters, or alternative root namespaces.
- Use the default Symfony `App\` namespace and the repository's existing Symfony-oriented layer structure.
- Keep comments and technical documentation in English.
- Never perform broad recursive deletion. Delete only paths proven obsolete by the current tree and report every deletion.

## Identity canon

### Root identity

- `VendorEntity` is the primary business root.
- `VendorEntity.id` is the PostgreSQL primary key and the canonical cross-system identifier.
- `VendorSecurityEntity` is the technical authentication/security extension of `VendorEntity`.
- `VendorSecurityEntity` has a one-to-one shared-primary-key association with `VendorEntity`.
- Login, password hash, credentials, and security metadata belong to `VendorSecurityEntity`.
- The shared primary key is the technical user/security ID, Vendor ID, and effective tenant identifier.
- Do not create a separate `TenantEntity`, `tenant_id`, or parallel tenant identity.

### Lifecycle identity

Objecting stores lifecycle actor identifiers as opaque scalar strings. In the current Objecting API, the actor arguments and values are `?string`.

The values represent the same canonical cross-system identity rooted at `VendorEntity.id`:

- `object_created_by`: identity that created the row.
- `object_modified_by`: identity that last modified the row.
- `object_deleted_by`: identity that soft-deleted the row.

Do not add a second tenant coordinate beside these fields merely because the platform is multitenant.

## Objecting ownership boundary

Objecting owns reusable system-field vocabulary and implementation:

- `App\Objecting\Embeddable\Object*Embeddable`
- `App\Objecting\EntityTrait\Embeddable\Object*EmbeddableTrait`
- `App\Objecting\EntityInterface\Object*Interface`
- canonical `object_*` Doctrine column names

Each consumer component owns:

- its business Entity classes;
- the Doctrine primary key `id`;
- Doctrine migrations and table ownership;
- business relations, including a real `VendorEntity` relation when business semantics require one;
- repositories, queries, DTOs, Forms, serializers, fixtures, tests, and runtime behavior.

Do not move business fields into Objecting merely because their names resemble a system field.

## Canonical lifecycle matrix

| Legacy semantic or alias | Canonical Objecting pack | Canonical column | Canonical PHP read/write surface | Required action |
|---|---|---|---|---|
| `created`, `createdAt`, `created_at`, `dateCreated`, `creationDate` | `object_audit` | `object_created_at` | `getObjectCreatedAt()`; seed with `initializeObjectAudit($createdAt, $createdBy)` | Migrate data and remove the local field, mapping, getter, setter, and alias. |
| `createdBy`, `created_by`, `creator`, `creatorId`, `createdUserId` when it is lifecycle attribution | `object_audit` | `object_created_by` | `getObjectCreatedBy()` | Store the canonical Vendor/security shared ID as a string. Do not create `tenant_id`. |
| `updated`, `updatedAt`, `updated_at`, `lastUpdatedAt`, `modified`, `modifiedAt`, `modified_at` | `object_audit` | `object_modified_at` | `getObjectModifiedAt()`; write with `touchModified($modifiedAt, $modifiedBy)` | Canonical vocabulary is **modified**, never updated. Remove updated/modified local aliases after migration. |
| `updatedBy`, `updated_by`, `lastUpdatedBy`, `modifiedBy`, `modified_by` | `object_audit` | `object_modified_by` | `getObjectModifiedBy()`; write with `touchModified()` | Backfill the canonical column and remove local actor fields and aliases. |
| `deleted`, `isDeleted`, `deletedFlag`, `softDeleted` | `object_soft_delete` | `object_deleted` | `isObjectDeleted()` | Replace local boolean mapping and soft-delete trait. |
| `deletedAt`, `deleted_at`, `removedAt`, `archivedAt` only when it means soft deletion | `object_soft_delete` | `object_deleted_at` | `getObjectDeletedAt()`; `deleteObject($deletedBy, $deletedAt)` | Do not map business archival to soft deletion without proof. |
| `deletedBy`, `deleted_by`, `removedBy` only when it means soft deletion attribution | `object_soft_delete` | `object_deleted_by` | `getObjectDeletedBy()`; `deleteObject()` | Store the canonical shared ID as a string. |
| `restore`, `undelete`, `unremove` for soft deletion | `object_soft_delete` | all soft-delete columns | `restoreObject()` | Clear deleted flag, timestamp, and actor through Objecting. |

### Canonical consumer composition

For audit fields, the consumer Entity should implement and use:

```php
use App\Objecting\EntityInterface\ObjectAuditedInterface;
use App\Objecting\EntityTrait\Embeddable\ObjectAuditEmbeddableTrait;

final class ExampleEntity implements ObjectAuditedInterface
{
    use ObjectAuditEmbeddableTrait;
}
```

For soft deletion:

```php
use App\Objecting\EntityInterface\ObjectSoftDeletableInterface;
use App\Objecting\EntityTrait\Embeddable\ObjectSoftDeleteEmbeddableTrait;

final class ExampleEntity implements ObjectSoftDeletableInterface
{
    use ObjectSoftDeleteEmbeddableTrait;
}
```

Explicit constructor initialization is preferred when the creator identity is available:

```php
$this->initializeObjectAudit(createdBy: $vendorId);
$this->initializeObjectSoftDelete();
```

Lazy initialization provided by Objecting is valid, but it does not invent a creator ID. Runtime creation services must pass the current canonical identity when lifecycle attribution is required.

### Consumer API rule

Use only the canonical Object-prefixed methods in migrated consumer code:

- `getObjectCreatedAt()`
- `getObjectCreatedBy()`
- `getObjectModifiedAt()`
- `getObjectModifiedBy()`
- `touchModified()`
- `isObjectDeleted()`
- `getObjectDeletedAt()`
- `getObjectDeletedBy()`
- `deleteObject()`
- `restoreObject()`

Do not introduce new dependencies on transitional aliases such as `getDeletedAt()`, `getDeletedBy()`, `delete()`, or `restore()` even if Objecting temporarily exposes them.

## Tenant cleanup decision matrix

Search for all of the following, including case and naming variants:

```text
tenant_id
tenantId
TenantEntity
TenantInterface
TenantAware
TenantContext
CurrentTenant
TenantResolver
TenantOwned
organization_id
organizationId
owner_id
ownerId
scope_id
scopeId
```

Classify every occurrence before editing.

| Existing use | Canonical interpretation | Migration action |
|---|---|---|
| `tenant_id` duplicates the same Vendor/security shared ID already written to lifecycle fields | Technical tenancy drift | Backfill missing `object_created_by`, `object_modified_by`, or `object_deleted_by` from the old value according to the actual lifecycle event; then remove the tenant property, Doctrine mapping, index, FK, DTO/Form/API field, serializer group, query filter, fixture value, and test expectation. |
| `tenant_id` is used only to resolve the current authenticated context | Runtime security context, not persisted data | Resolve the shared ID from `VendorSecurityEntity`/`VendorEntity`; pass it to creation, modification, and deletion operations. Remove persisted tenant state. |
| `tenant_id` is a genuine business relation to the Vendor root | Business Vendor association, not multitenancy infrastructure | Preserve the relation under explicit domain vocabulary such as `vendor`/`vendor_id`. Keep it in the owning component, not Objecting. Do not duplicate it with `tenant_id`. |
| `tenant_id` differs from lifecycle actor IDs in existing data | Unresolved semantic conflict | Stop automatic migration for that Entity. Produce a report with representative rows, mappings, and call sites. Do not guess or destroy data. |
| `organization`, `owner`, or `scope` carries a real business meaning | Business field | Keep it under its real business vocabulary. Never rename it to tenant or lifecycle fields mechanically. |
| `Tenant*` type exists only as a generic abstraction with no distinct data or behavior | Architecture drift | Replace it with the existing Vendor/security identity or remove it, then repair all callers and tests. |

### Forbidden tenant transformations

Never do any of the following:

- add `tenant_id` to every Entity;
- create `TenantEntity` above `VendorEntity`;
- store both `vendor_id` and `tenant_id` for the same identity;
- replace every `tenant_id` blindly with `object_modified_by`;
- infer ownership from column names without tracing data and runtime behavior;
- remove a true business `VendorEntity` relation merely because audit fields exist;
- retain dead tenant aliases for backward compatibility unless the user explicitly requires a staged public-API transition.

## Full Objecting field-pack matrix

Adopt a field pack only after confirming that the local field has the same system-level semantics.

| Pack | Trait | Interface | Canonical columns | Typical legacy candidates |
|---|---|---|---|---|
| `object_identity` | `ObjectIdentityEmbeddableTrait` | `ObjectIdentifiedInterface` | `object_uuid`, `object_slug` | `uuid`, `guid`, technical `slug` |
| `object_audit` | `ObjectAuditEmbeddableTrait` | `ObjectAuditedInterface` | `object_created_at`, `object_modified_at`, `object_created_by`, `object_modified_by` | timestamps and blame/audit fields |
| `object_title` | `ObjectTitleEmbeddableTrait` | `ObjectTitledInterface` | `object_first_title`, `object_middle_title`, `object_last_title` | `name`, `title`, `label`, `displayName`, `subtitle`, `summary`, `shortDescription`, `description` only through an explicit per-Entity alias decision |
| `object_publication` | `ObjectPublicationEmbeddableTrait` | `ObjectPublishableInterface` | `object_published`, `object_published_at` | generic publication flags/timestamps |
| `object_soft_delete` | `ObjectSoftDeleteEmbeddableTrait` | `ObjectSoftDeletableInterface` | `object_deleted`, `object_deleted_at`, `object_deleted_by` | generic soft-delete fields |
| `object_version` | `ObjectVersionEmbeddableTrait` | `ObjectVersionedInterface` | `object_version`, `object_etag` | generic optimistic version/etag |
| `object_locale` | `ObjectLocaleEmbeddableTrait` | `ObjectLocaleAwareInterface` | `object_locale`, `object_timezone` | generic locale/timezone |
| `object_token` | `ObjectTokenEmbeddableTrait` | `ObjectTokenizedInterface` | `object_token`, `object_token_expires_at` | generic technical token and expiry; never passwords or API secrets |
| `object_restriction` | `ObjectRestrictionEmbeddableTrait` | `ObjectRestrictableInterface` | `object_allowed_roles`, `object_ip_whitelist` | generic role/IP restrictions |
| `object_lock` | `ObjectLockEmbeddableTrait` | `ObjectLockableInterface` | `object_locked_at`, `object_locked_by` | generic application lock attribution |
| `object_workflow` | `ObjectWorkflowEmbeddableTrait` | `ObjectWorkflowAwareInterface` | `object_workflow_state`, `object_workflow_context` | generic workflow state/context |
| `object_config` | `ObjectConfigEmbeddableTrait` | `ObjectConfigurableInterface` | `object_config` | generic object configuration JSON |
| `object_code` | `ObjectCodeEmbeddableTrait` | `ObjectCodedInterface` | `object_code` | generic technical/business-neutral code only |
| `object_state` | `ObjectStateEmbeddableTrait` | `ObjectStatefulInterface` | `object_active`, `object_enabled`, `object_status` | generic activation/enabling/status only; preserve domain-specific state machines |
| `object_source` | `ObjectSourceEmbeddableTrait` | `ObjectSourcedInterface` | `object_source`, `object_provider`, `object_external_id`, `object_source_type` | generic import/provider/external-reference metadata |
| `object_fingerprint` | `ObjectFingerprintEmbeddableTrait` | `ObjectFingerprintedInterface` | `object_hash`, `object_checksum`, `object_algorithm` | generic integrity/fingerprint metadata |

`ObjectReferenceEmbeddable` is a separate optional reference surface. Do not replace Doctrine business associations with it mechanically.

## Ecosystem discovery protocol

When asked to migrate the whole platform:

1. Start from the selected ecosystem root, normally the parent directory containing sibling repositories.
2. Discover candidate repositories by `composer.json`, `src/`, Symfony `bin/console`, bundle classes, or application kernels.
3. Exclude `.git/`, `vendor/`, `node_modules/`, `var/`, generated caches, build artifacts, and archived snapshots.
4. Identify each repository's current branch/tree and existing `AGENTS.md` instructions.
5. Scan PHP, Doctrine attributes/XML/YAML, migrations, raw SQL, DQL, query builders, serializers, Forms, DTOs, API contracts, fixtures, tests, documentation, and configuration.
6. Build an inventory before mutation. Record every legacy field, method, trait, interface, column, index, FK, and alias with file paths.
7. Group occurrences by Entity and semantics. Do not use a global search-and-replace.
8. Migrate one component at a time and keep it executable before moving to the next component.

## Per-Entity migration procedure

For every candidate Entity:

1. Determine whether the fields are generic system fields or component-specific business fields.
2. Select only the required Objecting field packs.
3. Add the canonical Objecting interface and embeddable trait to the Entity.
4. Seed embeddables in the constructor when explicit initial values are available.
5. Update all PHP callers to canonical Objecting methods.
6. Update repository criteria, DQL, raw SQL, serializers, Forms, DTOs, normalizers, templates, fixtures, and tests.
7. Create a Doctrine migration owned by the consumer component.
8. Preserve existing data through an explicit backfill.
9. Remove legacy mappings and aliases only after all reads and writes use Objecting.
10. Run the component's complete quality gates.

## Doctrine migration order

Use a data-safe staged migration. Adapt generated SQL to the component's actual PostgreSQL schema; do not invent table names or types.

1. Add the canonical `object_*` columns needed by the selected packs.
2. Keep new actor/timestamp columns nullable during backfill when required.
3. Copy data from legacy columns using deterministic SQL.
4. For tenant cleanup, backfill lifecycle actor columns only where the old tenant value has the confirmed lifecycle meaning.
5. Verify row counts and null/conflict counts.
6. Deploy code that reads and writes only canonical Objecting fields.
7. Remove obsolete indexes and foreign keys.
8. Drop legacy columns and aliases.
9. Add final constraints/defaults/indexes required by the canonical mapping.
10. Verify `doctrine:schema:validate` and ensure `doctrine:schema:update --dump-sql` has no unexpected drift.

Do not combine destructive column drops with an unverified backfill. Do not silently coerce conflicting IDs.

## Transaction and runtime rules

- Resolve the current canonical identity before entering the application operation.
- Use the same identity consistently inside the Doctrine transaction.
- On creation, seed `object_created_by`.
- On update, call `touchModified($at, $vendorId)`.
- On soft deletion, call `deleteObject($vendorId, $at)`.
- On restore, call `restoreObject()`; do not invent a tenant field.
- Async messages must carry the canonical Vendor ID when the worker needs lifecycle attribution or business Vendor context.
- Do not carry both `vendorId` and `tenantId` when they represent the same identity.

## Required cleanup surface

A migration is incomplete while any obsolete surface remains, including:

- local timestamp/blame/soft-delete traits duplicated by Objecting;
- old Entity properties and Doctrine mappings;
- legacy getters/setters and compatibility aliases;
- serializer groups and API fields for removed properties;
- Forms and DTOs exposing removed technical fields;
- repository filters using old columns;
- migrations or SQL that recreate obsolete columns;
- fixtures and factories writing old fields;
- tests asserting old names;
- tenant abstractions duplicating the Vendor/security shared ID;
- cache keys, messages, logs, or webhook payloads carrying redundant tenant IDs.

Historical migrations already executed in production are immutable unless the repository canon explicitly permits rewriting them. Add a new forward migration instead.

## Verification requirements

Use the scripts declared by each repository. At minimum, run when available:

```bash
composer validate
composer dump-autoload
composer test:quality
composer test
composer phpstan
php bin/console lint:container
php bin/console lint:yaml config
php bin/console doctrine:schema:validate
php bin/console doctrine:migrations:status
```

Also search again for forbidden and legacy tokens after migration.

A component is complete only when:

- runtime code uses canonical Objecting methods;
- Doctrine mappings use canonical `object_*` columns;
- data migration is explicit and reversible where practical;
- no duplicate local system-field implementation remains;
- no redundant tenant identity remains;
- real business Vendor relations remain intact;
- tests and static analysis pass;
- the final report lists modified, added, and deleted files separately.

## Required Codex deliverables

For an ecosystem-wide task, produce:

1. a pre-change inventory by component and Entity;
2. a classification of every tenant occurrence;
3. a migration matrix showing legacy field → Objecting field/method;
4. code and Doctrine migrations component by component;
5. an unresolved-conflicts report instead of guesses;
6. exact validation commands and results;
7. a touched-files patch/archive;
8. a deletion manifest containing only proven obsolete paths.

Do not claim completion when only Entity properties were renamed. Completion requires executable code, migrated data, updated queries/contracts, and passing gates.
