# Objecting Workspace Audit

> Historical evidence notice: occurrences of `tenant`, `owner`, `organization`, `scope`, and `updatedAt` below describe fields observed in sibling components. They are not canonical Objecting field packs. The current canon is documented in `docs/architecture/objecting-vendor-identity-canon.md`.

Scope: sibling workspace packages with a direct `Objecting` composer dependency, excluding `App`, `Objecting`, and `Interfacing`.
Method: scanned source, config, migrations, tests, docs, delivery, report, and related schema hints for the system-field names in the request plus local `Object*` clone surfaces.

## Global Findings

- Connected components audited: 32
- Repeated non-covered system tokens: 18
- Local Object* clone surfaces found: Addressing, Taxating

### Repeated non-covered tokens

| Token | Component count |
| --- | ---: |
| `id` | 32 |
| `name` | 32 |
| `status` | 30 |
| `title` | 30 |
| `enabled` | 29 |
| `source` | 29 |
| `active` | 28 |
| `description` | 28 |
| `provider` | 28 |
| `scope` | 28 |
| `owner` | 27 |
| `hash` | 25 |
| `priority` | 25 |
| `visibility` | 22 |
| `tenant` | 20 |
| `checksum` | 14 |
| `organization` | 4 |
| `externalId` | 2 |

## Component: Accessing

### Existing local system fields/traits
- `object_audit`: `createdAt`, `updatedAt`
- `object_version`: `version`
- `object_locale`: `locale`, `timezone`
- `object_token`: `expiresAt`, `token`
- `object_workflow`: `lifecycle`, `state`, `workflow`
- `object_config`: `config`, `metadata`, `options`
- `object_code`: `code`

### Already covered by Objecting
- `object_audit`
- `object_version`
- `object_locale`
- `object_token`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `description`, `enabled`, `hash`, `id`, `name`, `owner`, `priority`, `provider`, `scope`, `source`, `status`, `title`, `visibility`

### Should remain local/business-specific
- Authentication, recovery, verification, and account-session surface.
- Representative local surface: `AccessAccountSessionEntity`, `AccessRecoveryCodeEntity`, `AccessVerificationChallengeEntity`

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `description` -> `object_title-or-object_description` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium
- `hash` -> `object_fingerprint` -> confidence: low

### Risks / notes
- Plain `id` is widespread here; Objecting currently does not model a generic id pack, so table/PK conventions should stay under component ownership.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Addressing

### Existing local system fields/traits
- `object_identity`: `slug`, `uuid`
- `object_audit`: `createdAt`, `createdBy`, `updatedAt`
- `object_title`: `firstTitle`, `lastTitle`, `middleTitle`
- `object_publication`: `published`, `publishedAt`
- `object_soft_delete`: `deletedAt`, `deletedBy`
- `object_version`: `revision`, `version`
- `object_token`: `expiresAt`, `token`
- `object_lock`: `lockedAt`, `lockedBy`
- `object_workflow`: `lifecycle`, `state`, `workflow`
- `object_config`: `config`, `metadata`, `options`, `settings`
- `object_code`: `code`
- Local legacy clones exist: `src/EntityTrait/ObjectTrait.php` and `src/EntityTrait/ObjectAuditTrait.php`.

### Already covered by Objecting
- `object_identity`
- `object_audit`
- `object_title`
- `object_publication`
- `object_soft_delete`
- `object_version`
- `object_token`
- `object_lock`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `description`, `enabled`, `hash`, `id`, `name`, `owner`, `priority`, `provider`, `scope`, `source`, `status`, `tenant`, `title`

### Should remain local/business-specific
- Address records, evidence snapshots, validation, and governance.
- Representative local surface: `AddressEntity`, `AddressEvidenceSnapshotEntity`, `AddressIndexEntity`, `AddressOutboxEntity`, `ObjectAuditTrait`, `ObjectTrait`

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `description` -> `object_title-or-object_description` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium
- `hash` -> `object_fingerprint` -> confidence: low

### Risks / notes
- The local `ObjectAuditTrait`/`ObjectTrait` pair looks like pre-Objecting boilerplate and should be compared with the canonical identity/audit packs before any extraction.
- Dense schema surface: 13 hints/files touched, so schema drift is likely and should be validated against live Doctrine metadata.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Adjudicating

### Existing local system fields/traits
- `object_identity`: `slug`
- `object_audit`: `createdAt`
- `object_version`: `version`
- `object_token`: `token`
- `object_workflow`: `lifecycle`, `state`, `workflow`
- `object_config`: `config`, `metadata`, `options`
- `object_code`: `code`

### Already covered by Objecting
- `object_identity`
- `object_audit`
- `object_version`
- `object_token`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `checksum`, `description`, `enabled`, `hash`, `id`, `name`, `organization`, `owner`, `priority`, `provider`, `scope`, `source`, `status`, `title`

### Should remain local/business-specific
- Governance stabilization, decisioning, and execution readiness.

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `checksum` -> `object_fingerprint` -> confidence: low
- `description` -> `object_title-or-object_description` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium

### Risks / notes
- Plain `id` is widespread here; Objecting currently does not model a generic id pack, so table/PK conventions should stay under component ownership.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Analysing

### Existing local system fields/traits
- `object_audit`: `createdAt`, `updatedAt`
- `object_version`: `version`
- `object_locale`: `locale`
- `object_token`: `expiresAt`, `token`
- `object_workflow`: `state`, `workflow`
- `object_config`: `config`, `metadata`, `options`
- `object_code`: `code`

### Already covered by Objecting
- `object_audit`
- `object_version`
- `object_locale`
- `object_token`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `checksum`, `description`, `enabled`, `hash`, `id`, `name`, `owner`, `provider`, `scope`, `source`, `status`, `tenant`, `title`

### Should remain local/business-specific
- Analytics exports, alerts, metrics snapshots, and webhook notifications.
- Representative local surface: `AnalyticsAlertRuleEntity`, `AnalyticsExportJobEntity`, `AnalyticsMetricSnapshotEntity`

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `checksum` -> `object_fingerprint` -> confidence: low
- `description` -> `object_title-or-object_description` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium

### Risks / notes
- Dense schema surface: 8 hints/files touched, so schema drift is likely and should be validated against live Doctrine metadata.
- Plain `id` is widespread here; Objecting currently does not model a generic id pack, so table/PK conventions should stay under component ownership.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Applicating

### Existing local system fields/traits
- `object_identity`: `slug`
- `object_audit`: `createdAt`, `updatedAt`
- `object_publication`: `published`, `publishedAt`
- `object_version`: `revision`, `version`
- `object_locale`: `locale`, `timezone`
- `object_token`: `token`
- `object_workflow`: `lifecycle`, `state`, `workflow`
- `object_config`: `config`, `metadata`, `options`
- `object_code`: `code`

### Already covered by Objecting
- `object_identity`
- `object_audit`
- `object_publication`
- `object_version`
- `object_locale`
- `object_token`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `checksum`, `description`, `enabled`, `hash`, `id`, `name`, `owner`, `priority`, `provider`, `scope`, `source`, `status`, `tenant`, `title`

### Should remain local/business-specific
- Application process/runtime surface with lifecycle and publication hints.

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `checksum` -> `object_fingerprint` -> confidence: low
- `description` -> `object_title-or-object_description` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium

### Risks / notes
- Plain `id` is widespread here; Objecting currently does not model a generic id pack, so table/PK conventions should stay under component ownership.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Attaching

### Existing local system fields/traits
- `object_identity`: `uuid`
- `object_audit`: `createdAt`, `updatedAt`
- `object_soft_delete`: `deletedAt`
- `object_version`: `version`
- `object_locale`: `locale`
- `object_token`: `token`
- `object_workflow`: `state`, `workflow`
- `object_config`: `config`, `metadata`, `options`
- `object_code`: `code`

### Already covered by Objecting
- `object_identity`
- `object_audit`
- `object_soft_delete`
- `object_version`
- `object_locale`
- `object_token`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `checksum`, `description`, `enabled`, `hash`, `id`, `name`, `owner`, `priority`, `provider`, `scope`, `source`, `status`, `title`, `visibility`

### Should remain local/business-specific
- Attachments, metadata, visibility, and entity binding.

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `checksum` -> `object_fingerprint` -> confidence: low
- `description` -> `object_title-or-object_description` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium

### Risks / notes
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Billing

### Existing local system fields/traits
- `object_audit`: `createdAt`, `updatedAt`
- `object_publication`: `published`
- `object_version`: `version`
- `object_locale`: `locale`
- `object_token`: `token`
- `object_workflow`: `lifecycle`, `state`, `workflow`
- `object_config`: `config`, `metadata`, `options`
- `object_code`: `code`

### Already covered by Objecting
- `object_audit`
- `object_publication`
- `object_version`
- `object_locale`
- `object_token`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `checksum`, `description`, `enabled`, `hash`, `id`, `name`, `priority`, `provider`, `scope`, `source`, `status`, `tenant`, `title`

### Should remain local/business-specific
- Invoices, ledgers, projections, and billing storage.
- Representative local surface: `BillingInvoiceEntity`, `BillingLedgerEntity`

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `checksum` -> `object_fingerprint` -> confidence: low
- `description` -> `object_title-or-object_description` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium

### Risks / notes
- Dense schema surface: 8 hints/files touched, so schema drift is likely and should be validated against live Doctrine metadata.
- Plain `id` is widespread here; Objecting currently does not model a generic id pack, so table/PK conventions should stay under component ownership.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Bridging

### Existing local system fields/traits
- `object_identity`: `slug`
- `object_publication`: `published`, `publishedAt`
- `object_version`: `version`
- `object_locale`: `locale`
- `object_token`: `expiresAt`
- `object_workflow`: `lifecycle`, `state`
- `object_config`: `config`, `metadata`, `options`, `settings`
- `object_code`: `code`

### Already covered by Objecting
- `object_identity`
- `object_publication`
- `object_version`
- `object_locale`
- `object_token`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `checksum`, `enabled`, `id`, `name`, `owner`, `provider`, `scope`, `source`, `status`, `tenant`, `title`, `visibility`

### Should remain local/business-specific
- Bridge adapters between backend payloads and interfacing screens.

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `checksum` -> `object_fingerprint` -> confidence: low
- `enabled` -> `object_state` -> confidence: medium
- `id` -> `object_identity` -> confidence: low

### Risks / notes
- Plain `id` is widespread here; Objecting currently does not model a generic id pack, so table/PK conventions should stay under component ownership.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Cataloging

### Existing local system fields/traits
- `object_identity`: `slug`, `uuid`
- `object_audit`: `createdAt`, `createdBy`, `updatedAt`
- `object_publication`: `published`, `publishedAt`
- `object_version`: `etag`, `version`
- `object_locale`: `locale`, `timezone`
- `object_token`: `expiresAt`, `token`
- `object_workflow`: `lifecycle`, `state`, `workflow`
- `object_config`: `config`, `metadata`, `options`, `settings`
- `object_code`: `code`

### Already covered by Objecting
- `object_identity`
- `object_audit`
- `object_publication`
- `object_version`
- `object_locale`
- `object_token`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `description`, `enabled`, `hash`, `id`, `name`, `organization`, `owner`, `priority`, `provider`, `scope`, `source`, `status`, `tenant`, `title`, `visibility`

### Should remain local/business-specific
- Category/catalog syndication, search, attachments, and projections.
- Representative local surface: `CatalogCategoryAttachmentEntity`, `CatalogCategoryAuditEntity`, `CatalogCategoryEntity`, `CatalogCategoryIdempotencyEntity`, `CatalogCategoryProjectionEntity`, `CatalogOutboxMessageEntity`

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `description` -> `object_title-or-object_description` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium
- `hash` -> `object_fingerprint` -> confidence: low

### Risks / notes
- Dense schema surface: 31 hints/files touched, so schema drift is likely and should be validated against live Doctrine metadata.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Commercializing

### Existing local system fields/traits
- `object_identity`: `slug`, `uuid`
- `object_audit`: `createdAt`, `updatedAt`
- `object_publication`: `published`
- `object_version`: `etag`, `version`
- `object_locale`: `locale`
- `object_token`: `expiresAt`, `token`
- `object_workflow`: `state`
- `object_config`: `config`, `metadata`, `options`, `settings`
- `object_code`: `code`

### Already covered by Objecting
- `object_identity`
- `object_audit`
- `object_publication`
- `object_version`
- `object_locale`
- `object_token`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `description`, `enabled`, `hash`, `id`, `name`, `owner`, `priority`, `provider`, `scope`, `source`, `status`, `tenant`, `title`, `visibility`

### Should remain local/business-specific
- Commerce orchestration, ledgers, pricing, shipping, taxation, and payouts.
- Representative local surface: `LedgerAccountEntity`, `LedgerEntryEntity`, `RefundTransactionEntity`, `PayoutAccountEntity`, `PayoutBatchEntity`, `RefundPolicyEntity`

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `description` -> `object_title-or-object_description` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium
- `hash` -> `object_fingerprint` -> confidence: low

### Risks / notes
- Dense schema surface: 47 hints/files touched, so schema drift is likely and should be validated against live Doctrine metadata.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Commissioning

### Existing local system fields/traits
- `object_identity`: `uuid`
- `object_audit`: `createdAt`
- `object_version`: `version`
- `object_config`: `config`, `metadata`, `options`
- `object_code`: `code`

### Already covered by Objecting
- `object_identity`
- `object_audit`
- `object_version`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `description`, `id`, `name`, `priority`, `source`, `status`, `title`

### Should remain local/business-specific
- Commission plans, payouts, and operational commission state.

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `description` -> `object_title-or-object_description` -> confidence: medium
- `id` -> `object_identity` -> confidence: low
- `name` -> `object_title-or-object_label` -> confidence: low

### Risks / notes
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Cruding

### Existing local system fields/traits
- `object_identity`: `slug`
- `object_audit`: `createdAt`, `createdBy`, `updatedAt`, `updatedBy`
- `object_publication`: `published`
- `object_locale`: `locale`
- `object_token`: `token`
- `object_workflow`: `state`
- `object_config`: `config`, `metadata`

### Already covered by Objecting
- `object_identity`
- `object_audit`
- `object_publication`
- `object_locale`
- `object_token`
- `object_workflow`
- `object_config`

### Missing in Objecting but repeated/potentially systemic
- `active`, `enabled`, `id`, `name`, `owner`, `provider`, `scope`, `source`, `status`, `title`, `visibility`

### Should remain local/business-specific
- CRUD bridge, object meta, relation, and visibility management.

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium
- `id` -> `object_identity` -> confidence: low
- `name` -> `object_title-or-object_label` -> confidence: low

### Risks / notes
- Plain `id` is widespread here; Objecting currently does not model a generic id pack, so table/PK conventions should stay under component ownership.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Currencing

### Existing local system fields/traits
- `object_version`: `version`
- `object_locale`: `locale`, `timezone`
- `object_token`: `token`
- `object_workflow`: `lifecycle`, `state`, `workflow`
- `object_config`: `config`, `metadata`, `options`
- `object_code`: `code`

### Already covered by Objecting
- `object_version`
- `object_locale`
- `object_token`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `description`, `enabled`, `hash`, `id`, `name`, `owner`, `priority`, `provider`, `scope`, `source`, `status`, `title`, `visibility`

### Should remain local/business-specific
- Currency definition and runtime exchange surface.

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `description` -> `object_title-or-object_description` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium
- `hash` -> `object_fingerprint` -> confidence: low

### Risks / notes
- Plain `id` is widespread here; Objecting currently does not model a generic id pack, so table/PK conventions should stay under component ownership.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Discovering

### Existing local system fields/traits
- `object_audit`: `updatedAt`
- `object_publication`: `published`
- `object_version`: `version`
- `object_locale`: `locale`, `timezone`
- `object_token`: `token`
- `object_workflow`: `lifecycle`, `state`, `workflow`
- `object_config`: `config`, `metadata`, `options`, `settings`
- `object_code`: `code`

### Already covered by Objecting
- `object_audit`
- `object_publication`
- `object_version`
- `object_locale`
- `object_token`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `description`, `enabled`, `hash`, `id`, `name`, `priority`, `provider`, `scope`, `source`, `status`, `title`, `visibility`

### Should remain local/business-specific
- Discovery taxonomy, index documents, and canon audits.
- Representative local surface: `DiscoveryIndexDocumentEntity`

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `description` -> `object_title-or-object_description` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium
- `hash` -> `object_fingerprint` -> confidence: low

### Risks / notes
- Plain `id` is widespread here; Objecting currently does not model a generic id pack, so table/PK conventions should stay under component ownership.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Exchanging

### Existing local system fields/traits
- `object_identity`: `uuid`
- `object_version`: `version`
- `object_locale`: `locale`
- `object_token`: `token`
- `object_workflow`: `lifecycle`, `state`, `workflow`
- `object_config`: `config`, `metadata`, `options`
- `object_code`: `code`

### Already covered by Objecting
- `object_identity`
- `object_version`
- `object_locale`
- `object_token`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `description`, `enabled`, `hash`, `id`, `name`, `owner`, `priority`, `provider`, `scope`, `source`, `status`, `title`

### Should remain local/business-specific
- Exchange runtime and exchange entity surface.
- Representative local surface: `Exchange`

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `description` -> `object_title-or-object_description` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium
- `hash` -> `object_fingerprint` -> confidence: low

### Risks / notes
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Faceting

### Existing local system fields/traits
- `object_audit`: `createdAt`, `updatedAt`
- `object_config`: `config`, `metadata`, `options`
- `object_code`: `code`

### Already covered by Objecting
- `object_audit`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `id`, `name`, `scope`, `title`, `visibility`

### Should remain local/business-specific
- Facet registry and taxonomy-like metadata surface.

### Migration candidates
- `id` -> `object_identity` -> confidence: low
- `name` -> `object_title-or-object_label` -> confidence: low
- `scope` -> `object_scope` -> confidence: low
- `title` -> `object_title-or-object_label` -> confidence: medium

### Risks / notes
- Plain `id` is widespread here; Objecting currently does not model a generic id pack, so table/PK conventions should stay under component ownership.

## Component: Facting

### Existing local system fields/traits
- `object_audit`: `createdAt`, `updatedAt`
- `object_version`: `version`
- `object_token`: `expiresAt`
- `object_workflow`: `state`
- `object_config`: `config`, `metadata`, `settings`
- `object_code`: `code`

### Already covered by Objecting
- `object_audit`
- `object_version`
- `object_token`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `checksum`, `description`, `enabled`, `hash`, `id`, `name`, `provider`, `source`, `status`, `tenant`, `visibility`

### Should remain local/business-specific
- Fact/outbox persistence and infra glue.

### Migration candidates
- `checksum` -> `object_fingerprint` -> confidence: low
- `description` -> `object_title-or-object_description` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium
- `hash` -> `object_fingerprint` -> confidence: low

### Risks / notes
- Plain `id` is widespread here; Objecting currently does not model a generic id pack, so table/PK conventions should stay under component ownership.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Indexing

### Existing local system fields/traits
- `object_identity`: `uuid`
- `object_audit`: `createdAt`, `updatedAt`
- `object_publication`: `published`
- `object_version`: `version`
- `object_token`: `token`
- `object_workflow`: `lifecycle`, `state`, `workflow`
- `object_config`: `config`, `metadata`, `options`, `settings`
- `object_code`: `code`

### Already covered by Objecting
- `object_identity`
- `object_audit`
- `object_publication`
- `object_version`
- `object_token`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `description`, `enabled`, `id`, `name`, `owner`, `priority`, `provider`, `scope`, `source`, `status`, `tenant`, `title`, `visibility`

### Should remain local/business-specific
- Search index jobs, outboxes, and index state.
- Representative local surface: `IndexJob`, `IndexOutbox`, `IndexPointer`

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `description` -> `object_title-or-object_description` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium
- `id` -> `object_identity` -> confidence: low

### Risks / notes
- Dense schema surface: 27 hints/files touched, so schema drift is likely and should be validated against live Doctrine metadata.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Localizing

### Existing local system fields/traits
- `object_locale`: `locale`
- `object_config`: `config`, `options`
- `object_code`: `code`

### Already covered by Objecting
- `object_locale`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `description`, `enabled`, `id`, `name`, `owner`, `priority`, `title`

### Should remain local/business-specific
- Locale, naming, and localization runtime.

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `description` -> `object_title-or-object_description` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium
- `id` -> `object_identity` -> confidence: low

### Risks / notes
- Plain `id` is widespread here; Objecting currently does not model a generic id pack, so table/PK conventions should stay under component ownership.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Locating

### Existing local system fields/traits
- `object_identity`: `slug`
- `object_audit`: `createdAt`, `updatedAt`
- `object_version`: `revision`, `version`
- `object_locale`: `locale`, `timezone`
- `object_token`: `token`
- `object_workflow`: `lifecycle`, `state`, `workflow`
- `object_config`: `config`, `metadata`, `options`, `settings`
- `object_code`: `code`

### Already covered by Objecting
- `object_identity`
- `object_audit`
- `object_version`
- `object_locale`
- `object_token`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `checksum`, `description`, `enabled`, `hash`, `id`, `name`, `owner`, `priority`, `provider`, `scope`, `source`, `status`, `tenant`, `title`, `visibility`

### Should remain local/business-specific
- Locator, provider governance, and retention sweeper surface.

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `checksum` -> `object_fingerprint` -> confidence: low
- `description` -> `object_title-or-object_description` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium

### Risks / notes
- Plain `id` is widespread here; Objecting currently does not model a generic id pack, so table/PK conventions should stay under component ownership.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Messaging

### Existing local system fields/traits
- `object_identity`: `slug`, `uuid`
- `object_audit`: `createdAt`, `createdBy`, `updatedAt`, `updatedBy`
- `object_publication`: `published`
- `object_soft_delete`: `deletedAt`, `deletedBy`
- `object_version`: `etag`, `revision`, `version`
- `object_locale`: `locale`
- `object_token`: `expiresAt`, `token`
- `object_workflow`: `lifecycle`, `state`, `workflow`
- `object_config`: `config`, `metadata`, `options`, `settings`
- `object_code`: `code`

### Already covered by Objecting
- `object_identity`
- `object_audit`
- `object_publication`
- `object_soft_delete`
- `object_version`
- `object_locale`
- `object_token`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `checksum`, `description`, `enabled`, `hash`, `id`, `name`, `organization`, `owner`, `priority`, `provider`, `scope`, `source`, `status`, `tenant`, `title`, `visibility`

### Should remain local/business-specific
- Messaging, threads, attachments, moderation, and tenant/security concerns.
- Representative local surface: `MessageAbuseLogEntity`, `MessageAbusePenaltyEntity`, `MessageAbuseTimeoutEntity`, `MessageAttachmentFileEntity`, `MessageAttachmentPolicyEntity`, `MessageAttachmentQuotaEntity`

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `checksum` -> `object_fingerprint` -> confidence: low
- `description` -> `object_title-or-object_description` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium

### Risks / notes
- Dense schema surface: 236 hints/files touched, so schema drift is likely and should be validated against live Doctrine metadata.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Observabiliting

### Existing local system fields/traits
- `object_version`: `version`
- `object_token`: `token`
- `object_config`: `config`
- `object_code`: `code`

### Already covered by Objecting
- `object_version`
- `object_token`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `enabled`, `id`, `name`, `owner`, `priority`, `provider`, `status`, `tenant`, `title`

### Should remain local/business-specific
- Observability, signals, metrics, and provider status surface.

### Migration candidates
- `enabled` -> `object_state` -> confidence: medium
- `id` -> `object_identity` -> confidence: low
- `name` -> `object_title-or-object_label` -> confidence: low
- `owner` -> `object_scope-or-object_ownership` -> confidence: low

### Risks / notes
- Plain `id` is widespread here; Objecting currently does not model a generic id pack, so table/PK conventions should stay under component ownership.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Ordering

### Existing local system fields/traits
- `object_identity`: `slug`, `uuid`
- `object_audit`: `createdAt`, `updatedAt`
- `object_publication`: `published`
- `object_version`: `etag`, `revision`, `version`
- `object_locale`: `locale`, `timezone`
- `object_token`: `expiresAt`, `token`
- `object_workflow`: `lifecycle`, `state`, `workflow`
- `object_config`: `config`, `metadata`, `options`, `settings`
- `object_code`: `code`

### Already covered by Objecting
- `object_identity`
- `object_audit`
- `object_publication`
- `object_version`
- `object_locale`
- `object_token`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `checksum`, `description`, `enabled`, `externalId`, `hash`, `id`, `name`, `owner`, `priority`, `provider`, `scope`, `source`, `status`, `tenant`, `title`, `visibility`

### Should remain local/business-specific
- Orders, payments, refunds, outbox, and idempotency surface.

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `checksum` -> `object_fingerprint` -> confidence: low
- `description` -> `object_title-or-object_description` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium

### Risks / notes
- Dense schema surface: 44 hints/files touched, so schema drift is likely and should be validated against live Doctrine metadata.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Paging

### Existing local system fields/traits
- `object_identity`: `slug`
- `object_audit`: `createdAt`, `updatedAt`
- `object_publication`: `published`, `publishedAt`
- `object_version`: `etag`, `revision`, `version`
- `object_locale`: `locale`
- `object_token`: `expiresAt`, `token`
- `object_lock`: `lockedAt`
- `object_workflow`: `lifecycle`, `state`
- `object_config`: `config`, `metadata`, `options`
- `object_code`: `code`

### Already covered by Objecting
- `object_identity`
- `object_audit`
- `object_publication`
- `object_version`
- `object_locale`
- `object_token`
- `object_lock`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `checksum`, `description`, `hash`, `id`, `name`, `owner`, `provider`, `scope`, `source`, `status`, `title`, `visibility`

### Should remain local/business-specific
- Pages, revisions, publication, grants, and attachments.
- Representative local surface: `PageAttachmentReference`, `PageGrant`, `PagePublication`, `PageRevision`

### Migration candidates
- `checksum` -> `object_fingerprint` -> confidence: low
- `description` -> `object_title-or-object_description` -> confidence: medium
- `hash` -> `object_fingerprint` -> confidence: low
- `id` -> `object_identity` -> confidence: low

### Risks / notes
- Plain `id` is widespread here; Objecting currently does not model a generic id pack, so table/PK conventions should stay under component ownership.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Paying

### Existing local system fields/traits
- `object_audit`: `createdAt`, `updatedAt`
- `object_publication`: `published`
- `object_version`: `version`
- `object_locale`: `locale`, `timezone`
- `object_token`: `expiresAt`, `token`
- `object_workflow`: `lifecycle`, `state`, `workflow`
- `object_config`: `config`, `metadata`, `options`
- `object_code`: `code`

### Already covered by Objecting
- `object_audit`
- `object_publication`
- `object_version`
- `object_locale`
- `object_token`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `description`, `enabled`, `externalId`, `hash`, `id`, `name`, `owner`, `priority`, `provider`, `scope`, `source`, `status`, `tenant`, `title`, `visibility`

### Should remain local/business-specific
- Payments, projections, idempotency, and dead-letter handling.
- Representative local surface: `PaymentDlqEntity`

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `description` -> `object_title-or-object_description` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium
- `externalId` -> `object_external_reference` -> confidence: low

### Risks / notes
- Dense schema surface: 15 hints/files touched, so schema drift is likely and should be validated against live Doctrine metadata.
- Plain `id` is widespread here; Objecting currently does not model a generic id pack, so table/PK conventions should stay under component ownership.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Projecting

### Existing local system fields/traits
- `object_identity`: `slug`, `uuid`
- `object_audit`: `createdAt`, `updatedAt`
- `object_publication`: `published`, `publishedAt`
- `object_soft_delete`: `deletedAt`
- `object_version`: `etag`, `version`
- `object_locale`: `locale`
- `object_token`: `expiresAt`, `token`
- `object_workflow`: `lifecycle`, `state`, `workflow`
- `object_config`: `config`, `metadata`, `options`, `settings`
- `object_code`: `code`

### Already covered by Objecting
- `object_identity`
- `object_audit`
- `object_publication`
- `object_soft_delete`
- `object_version`
- `object_locale`
- `object_token`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `description`, `enabled`, `hash`, `id`, `name`, `owner`, `priority`, `provider`, `scope`, `source`, `status`, `tenant`, `title`, `visibility`

### Should remain local/business-specific
- Projection, webhook, quota, SLA, and export surface.
- Representative local surface: `Project.orm`, `ProjectDocument.orm`, `ProjectImpact.orm`, `ProjectMedia.orm`, `ProjectReport.orm`, `ProjectingExportJobEntity`

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `description` -> `object_title-or-object_description` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium
- `hash` -> `object_fingerprint` -> confidence: low

### Risks / notes
- Dense schema surface: 53 hints/files touched, so schema drift is likely and should be validated against live Doctrine metadata.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Rolling

### Existing local system fields/traits
- `object_audit`: `createdAt`, `updatedAt`
- `object_publication`: `published`
- `object_version`: `etag`, `revision`, `version`
- `object_locale`: `locale`, `timezone`
- `object_token`: `expiresAt`, `token`
- `object_workflow`: `lifecycle`, `state`, `workflow`
- `object_config`: `config`, `metadata`, `options`
- `object_code`: `code`

### Already covered by Objecting
- `object_audit`
- `object_publication`
- `object_version`
- `object_locale`
- `object_token`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `description`, `enabled`, `hash`, `id`, `name`, `owner`, `provider`, `scope`, `source`, `status`, `tenant`, `title`, `visibility`

### Should remain local/business-specific
- Recovery/rollup infrastructure and registry state.

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `description` -> `object_title-or-object_description` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium
- `hash` -> `object_fingerprint` -> confidence: low

### Risks / notes
- Plain `id` is widespread here; Objecting currently does not model a generic id pack, so table/PK conventions should stay under component ownership.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Shipping

### Existing local system fields/traits
- `object_identity`: `slug`, `uuid`
- `object_audit`: `createdAt`, `updatedAt`, `updatedBy`
- `object_publication`: `published`, `publishedAt`
- `object_version`: `version`
- `object_locale`: `locale`, `timezone`
- `object_token`: `token`
- `object_workflow`: `lifecycle`, `state`, `workflow`
- `object_config`: `config`, `metadata`, `options`, `settings`
- `object_code`: `code`

### Already covered by Objecting
- `object_identity`
- `object_audit`
- `object_publication`
- `object_version`
- `object_locale`
- `object_token`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `description`, `enabled`, `hash`, `id`, `name`, `organization`, `owner`, `priority`, `provider`, `scope`, `source`, `status`, `tenant`, `title`, `visibility`

### Should remain local/business-specific
- Shipment, carrier, rate, policy, and fulfillment surface.
- Representative local surface: `OutboxEvent`, `Shipment`, `ShipmentCarrierCredentialEntity`, `ShipmentCarrierEntity`, `ShipmentCarrierZoneMapEntity`, `ShipmentPolicyEntity`

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `description` -> `object_title-or-object_description` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium
- `hash` -> `object_fingerprint` -> confidence: low

### Risks / notes
- Dense schema surface: 16 hints/files touched, so schema drift is likely and should be validated against live Doctrine metadata.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Subscripting

### Existing local system fields/traits
- `object_audit`: `createdAt`, `updatedAt`
- `object_version`: `version`
- `object_locale`: `locale`
- `object_token`: `expiresAt`, `token`
- `object_workflow`: `lifecycle`, `state`, `workflow`
- `object_config`: `config`, `metadata`, `options`
- `object_code`: `code`

### Already covered by Objecting
- `object_audit`
- `object_version`
- `object_locale`
- `object_token`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `description`, `enabled`, `hash`, `id`, `name`, `owner`, `priority`, `provider`, `scope`, `source`, `status`, `visibility`

### Should remain local/business-specific
- Subscriptions, billing hooks, and JSON responder surface.
- Representative local surface: `SubscriptionBillingHookEntity`

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `description` -> `object_title-or-object_description` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium
- `hash` -> `object_fingerprint` -> confidence: low

### Risks / notes
- Plain `id` is widespread here; Objecting currently does not model a generic id pack, so table/PK conventions should stay under component ownership.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Tagging

### Existing local system fields/traits
- `object_identity`: `slug`, `uuid`
- `object_audit`: `createdAt`, `updatedAt`
- `object_publication`: `published`
- `object_version`: `etag`, `version`
- `object_locale`: `locale`
- `object_token`: `token`
- `object_workflow`: `lifecycle`, `state`, `workflow`
- `object_config`: `config`, `metadata`, `options`
- `object_code`: `code`

### Already covered by Objecting
- `object_identity`
- `object_audit`
- `object_publication`
- `object_version`
- `object_locale`
- `object_token`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `checksum`, `description`, `enabled`, `hash`, `id`, `name`, `owner`, `priority`, `scope`, `source`, `status`, `tenant`, `title`, `visibility`

### Should remain local/business-specific
- Tagging, taxonomy, and relation graph surface.
- Representative local surface: `Tag`, `TagAssignment`, `TagAssignmentEffect`, `TagAuditLog`, `TagClassification`, `TagIdempotencyStore`

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `checksum` -> `object_fingerprint` -> confidence: low
- `description` -> `object_title-or-object_description` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium

### Risks / notes
- Dense schema surface: 16 hints/files touched, so schema drift is likely and should be validated against live Doctrine metadata.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Taxating

### Existing local system fields/traits
- `object_identity`: `slug`
- `object_audit`: `createdAt`, `createdBy`, `updatedAt`
- `object_soft_delete`: `deletedAt`
- `object_version`: `version`
- `object_locale`: `locale`, `timezone`
- `object_token`: `expiresAt`, `token`
- `object_workflow`: `lifecycle`, `state`, `workflow`
- `object_config`: `config`, `metadata`, `options`
- `object_code`: `code`
- Local legacy clones exist: `src/EntityTrait/Taxation/ObjectAuditTrait.php`, `src/EntityInterface/Common/ObjectTitleInterface.php`, and `src/EntityTrait/Taxation/TaxationObjectEntityTrait.php`.

### Already covered by Objecting
- `object_identity`
- `object_audit`
- `object_soft_delete`
- `object_version`
- `object_locale`
- `object_token`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `checksum`, `description`, `enabled`, `hash`, `id`, `name`, `owner`, `priority`, `provider`, `scope`, `source`, `status`, `tenant`, `title`

### Should remain local/business-specific
- Taxation, evidence, invoice, ledger, and jurisdiction surface.
- Representative local surface: `TaxationArchiveEventEntity`, `TaxationCertificateEntity`, `TaxationEntity`, `TaxationFactLedgerEntity`, `TaxationFactRecordEntity`, `TaxationIdempotencyEntity`

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `checksum` -> `object_fingerprint` -> confidence: low
- `description` -> `object_title-or-object_description` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium

### Risks / notes
- The local `ObjectAuditTrait` and `ObjectTitleInterface` clone the Objecting naming surface rather than consuming it; this is the strongest drift signal in the workspace.
- Dense schema surface: 15 hints/files touched, so schema drift is likely and should be validated against live Doctrine metadata.
- Plain `id` is widespread here; Objecting currently does not model a generic id pack, so table/PK conventions should stay under component ownership.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.

## Component: Vendoring

### Existing local system fields/traits
- `object_identity`: `slug`, `uuid`
- `object_audit`: `createdAt`, `updatedAt`
- `object_publication`: `published`, `publishedAt`
- `object_version`: `version`
- `object_locale`: `locale`, `timezone`
- `object_token`: `expiresAt`, `token`
- `object_workflow`: `lifecycle`, `state`, `workflow`
- `object_config`: `config`, `metadata`, `options`, `settings`
- `object_code`: `code`

### Already covered by Objecting
- `object_identity`
- `object_audit`
- `object_publication`
- `object_version`
- `object_locale`
- `object_token`
- `object_workflow`
- `object_config`
- `object_code`

### Missing in Objecting but repeated/potentially systemic
- `active`, `checksum`, `description`, `enabled`, `hash`, `id`, `name`, `owner`, `priority`, `provider`, `scope`, `source`, `status`, `tenant`, `title`, `visibility`

### Should remain local/business-specific
- Vendor profiles, transactions, docs, payouts, and catalog attachments.
- Representative local surface: `VendorAddressEntity`, `VendorApiKeyEntity`, `VendorAttachmentEntity`, `VendorCatalogCategoryBannerEntity`, `VendorCatalogCategoryChangeRequestEntity`, `VendorCatalogCategoryHtmlBlockEntity`

### Migration candidates
- `active` -> `object_state` -> confidence: medium
- `checksum` -> `object_fingerprint` -> confidence: low
- `description` -> `object_title-or-object_description` -> confidence: medium
- `enabled` -> `object_state` -> confidence: medium

### Risks / notes
- Dense schema surface: 31 hints/files touched, so schema drift is likely and should be validated against live Doctrine metadata.
- State/visibility semantics are broad and frequently overloaded; normalize carefully before extracting any new pack.
