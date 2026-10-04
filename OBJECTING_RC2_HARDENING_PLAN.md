# Objecting RC2 Hardening Plan

## Status

Objecting is currently treated as an RC1-level reusable Symfony/Doctrine component. It is already usable by consumer components, but RC2 must prove semantic stability, Doctrine composition safety, compatibility, operability, and production-level documentation.

This document is the canonical RC1-to-RC2 hardening plan for Objecting.

## Product responsibility

Objecting is the platform provider of reusable universal system-field packs for Doctrine entities.

Objecting owns:

- canonical `object_*` system-column vocabulary;
- Doctrine embeddables for universal system fields;
- Symfony-oriented entity traits and interfaces for explicit composition;
- field-pack manifests, profiles, semantic contracts, and compatibility metadata;
- package-level inspection, validation, diagnostic, and compatibility tooling;
- factual integration documentation for consumer components.

Consumer components own:

- business Entity classes and table ownership;
- Doctrine primary keys and migrations;
- business relations and validation;
- repositories, services, DTOs, Forms, serializers, controllers, routes, fixtures, and runtime behavior;
- selection and explicit composition of Objecting field packs.

Objecting must not become:

- a universal base Entity or god trait;
- a CRUD, routing, controller, API, or UI framework;
- a Doctrine migration owner for consumer tables;
- an attachment storage, file-processing, or binary-lifecycle component;
- a generic tenant, ownership, or authorization implementation;
- a hidden event-listener system that mutates consumer entities without explicit runtime calls.

## Attachment boundary

Attachment files, storage providers, MIME metadata, upload lifecycle, antivirus results, variants, access policy, and Doctrine associations belong to a dedicated attachment-owning component.

Objecting may own attachment-related fields only when a separate ecosystem audit proves that the fields are universal, business-neutral system metadata. No attachment association or storage behavior may be introduced into Objecting during RC2 hardening.

## RC2 product objectives

RC2 must prove all of the following:

1. Every field pack has one unambiguous universal meaning.
2. Every supported pack and profile composes correctly in Doctrine metadata and PostgreSQL schema.
3. Objecting changes cannot silently break consumer PHP APIs or database schemas.
4. Lifecycle behavior is defined as explicit state transitions with enforced invariants.
5. Public APIs and field-pack semantics are fully and factually documented.
6. Diagnostics exist without adding noisy runtime logging to embeddables.
7. At least three real consumer components pass adoption and compatibility checks.

## RC-critical track

### M1. Responsibility-boundary contract and gate

Create a normative architecture document and an executable gate that defines and enforces Objecting ownership.

The gate must reject Objecting-owned:

- Entity classes;
- Doctrine migrations;
- controllers and routes;
- repositories;
- DTOs and Forms;
- serializer and OpenAPI ownership;
- file-storage integration;
- Cruding declarations;
- `/src/Domain/` and Ports and Adapters structures.

Acceptance criteria:

- the boundary is documented in one canonical file;
- the gate runs from `composer test:quality`;
- the gate scans only repository-owned source and excludes dependencies and generated state.

### M2. Complete semantic field-pack manifests

Extend every `resources/field-pack/object-*.yaml` declaration with product-level semantics.

Required metadata:

- field-pack version and stability;
- purpose and universal/business-neutral classification;
- Doctrine type, PHP type, nullability, default, length, and generation policy for every column;
- mutation methods and read surface;
- invariants and valid state transitions;
- schema compatibility version;
- PHP public API compatibility version;
- Objecting, consumer, and Exposing ownership boundaries;
- security classification where relevant.

Acceptance criteria:

- all active packs contain the required keys;
- manifests are validated by executable tests;
- documentation is generated from or verified against the manifests to prevent drift.

### M3. Lifecycle invariant hardening

Define and test lifecycle state machines instead of testing only getters and setters.

Audit invariants:

- creation timestamp and creator are immutable after initialization;
- modified timestamp is null until modification;
- modified timestamp cannot precede creation;
- modification timestamp and actor change atomically.

Soft-delete invariants:

- active state has a false deleted flag and null deletion metadata;
- deleted state has a true flag and a non-null deletion timestamp;
- restore clears the complete deletion state;
- repeated delete and restore calls have explicit documented semantics.

Lock invariants:

- lock timestamp and actor form one coherent state;
- unlock clears the complete lock state;
- repeated lock attempts and actor replacement have explicit semantics;
- concurrency expectations are documented without pretending that an embeddable provides a distributed lock.

Acceptance criteria:

- deterministic transition-matrix tests exist;
- invalid transitions either fail explicitly or follow documented idempotent behavior;
- no hidden lifecycle mutation listener is introduced.

### M4. Doctrine composition integration suite

Create a minimal Symfony/Doctrine fixture application under tests.

Required fixture entities:

- baseline profile entity;
- content profile entity;
- lifecycle profile entity;
- security profile entity;
- localized profile entity;
- full supported-system profile entity.

Required tests:

- Doctrine metadata loading;
- exact column names and `columnPrefix: false` behavior;
- type, nullability, default, and length parity with manifests;
- schema generation;
- persist, clear, reload, and update round trips;
- lazy initialization before persistence;
- hydration without accidental embeddable replacement;
- partial update safety;
- supported field-pack combination matrix.

Database matrix:

- SQLite for fast development feedback;
- PostgreSQL as the authoritative RC gate.

Acceptance criteria:

- every supported profile passes metadata and persistence tests;
- PostgreSQL schema matches canonical manifests;
- no consumer migration ownership is moved into Objecting.

### M5. PHP and schema compatibility gates

Treat Objecting as publishing two separately versioned products:

- the PHP composition API;
- the database schema API.

Introduce machine-readable schema-version declarations and baseline comparison tools.

Breaking changes include:

- column rename or removal;
- Doctrine type, length, default, or nullability change;
- embedded prefix behavior change;
- public method removal or signature change;
- lifecycle semantic change;
- profile change that alters an existing effective field-pack selection.

Acceptance criteria:

- `composer test:schema-compatibility` exists;
- `composer test:public-api-compatibility` exists;
- breaking changes require an explicit version decision and migration guide;
- additive opt-in packs do not falsely trigger breaking-change reports.

### M6. Consumer inspection and doctor tooling

Add read-only Symfony diagnostic commands or equivalent package services:

- `objecting:inspect`;
- `objecting:manifest:validate`;
- `objecting:schema:compare`;
- `objecting:consumer:doctor`.

The tooling may inspect Doctrine metadata, field-pack declarations, legacy duplicate fields, and schema drift. It must not create entities, generate business code, write migrations, or mutate schemas.

Required outputs:

- human-readable console report;
- machine-readable JSON report;
- non-zero exit code for contract violations;
- stable diagnostic identifiers suitable for CI and observability ingestion.

### M7. Static-analysis hardening

Make PHPStan a blocking RC gate.

Targets:

- PHPStan `level: max`;
- analysis of `src`, `tests`, and `tools`;
- no unexplained baseline;
- explicit array shapes and generics for registries, manifests, contracts, and reports;
- no implicit mixed at public boundaries;
- correct immutable date and value-object typing.

Acceptance criteria:

- `composer phpstan` passes in CI and supported local execution environments;
- type suppressions include factual justification;
- PHPStan execution is part of the release checklist.

### M8. Public API and PHPDoc documentation coverage

Target 100 percent documentation coverage for public APIs and field-pack semantics, not mechanical comments on trivial private implementation details.

Mandatory documentation:

- every public interface, class, trait, constructor, and method;
- lifecycle invariants and mutation effects;
- exceptions and invalid states;
- manifest keys and versions;
- Doctrine mapping decisions;
- consumer composition and migration ownership;
- compatibility and deprecation policy.

PHPDoc must add semantic information, generics, array shapes, mutation contracts, or invariants. It must not merely repeat native PHP types.

Acceptance criteria:

- a documentation-coverage gate checks all public symbols;
- every field pack has a dedicated factual documentation page;
- examples compile or are validated by tests.

### M9. Security review of sensitive packs

Perform explicit threat and misuse reviews for:

- object token;
- object restriction;
- object config;
- object workflow;
- object source and external identifiers;
- object fingerprint.

Decisions must cover:

- secret and credential exclusion;
- token storage and expiry semantics;
- authorization versus declarative metadata boundaries;
- role and IP/CIDR normalization responsibility;
- JSON size and schema-version expectations;
- deterministic serialization where fingerprints depend on content;
- accidental serialization or API exposure risks.

Acceptance criteria:

- each sensitive pack has a documented threat decision;
- security invariants are executable where possible;
- Objecting does not claim to enforce authorization merely by storing restriction fields.

### M10. Production diagnostics and logging boundary

Do not log getters, setters, normal lifecycle changes, or routine Doctrine hydration.

Optional PSR-3 logging is allowed only in diagnostic, inspection, manifest-loading, and compatibility services.

Loggable conditions:

- invalid or incompatible manifest;
- unknown profile or pack;
- lifecycle invariant violation;
- schema mismatch;
- conflicting title alias;
- duplicate local system-field implementation;
- deprecated public API usage in development and test environments.

Acceptance criteria:

- embeddables have no logger dependency;
- diagnostic services expose stable event identifiers;
- JSON diagnostic reports can be consumed by the platform observability component;
- Objecting does not directly own Prometheus exporters or logging sinks.

### M11. Real consumer pilot validation

Select at least three consumer components:

1. a simple data component;
2. a lifecycle-heavy component;
3. a content or localized component.

Recommended initial candidates include Addressing, Taxating, and one content-oriented component such as Paging or Producting.

For each pilot:

- install Objecting through the real dependency mechanism;
- declare and compose field packs;
- validate Doctrine metadata and PostgreSQL schema;
- execute create, modify, delete, restore, and relevant lock/version scenarios;
- remove proven duplicate local system-field implementations;
- verify that Entity, migration, API, CRUD, and runtime ownership remains in the consumer.

Acceptance criteria:

- all pilot quality gates pass;
- unresolved semantic conflicts are reported rather than guessed;
- Objecting requires no consumer controller or route ownership.

### M12. Release and CI matrix

Required blocking gates:

- `composer validate --strict`;
- `composer audit --locked`;
