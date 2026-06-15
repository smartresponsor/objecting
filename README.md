# Objecting

Objecting is a Symfony-oriented foundation component for reusable object system field packs.

It is not a god entity, API framework, CRUD framework, host application, or persistence owner for consumer components. It provides small reusable primitives that backend components can compose into their own Doctrine entities.

## Canonical responsibility

Objecting owns the system field-pack vocabulary:

- `object_identity`
- `object_audit`
- `object_title`
- `object_publication`
- `object_soft_delete`
- `object_version`
- `object_locale`
- `object_token`
- `object_restriction`
- `object_lock`
- `object_workflow`
- `object_config`
- `object_code`

Each field pack is represented through:

```text
src/Embeddable/Object*Embeddable.php
src/EntityTrait/Embeddable/Object*EmbeddableTrait.php
src/EntityInterface/Object*Interface.php
resources/field-pack/object-*.yaml
```

## Universal title canon

All business objects may expose the canonical three-part title surface:

```text
firstTitle
middleTitle
lastTitle
```

These fields are system fields. Concrete components may alias them as business names such as `firstName`, `middleName`, `lastName`, `title`, `shortDescription`, `description`, `name`, or `summary`.

Objecting owns the canonical field pack. Consumer components own their local aliases, DTO labels, forms, and API exposure.

## Vendor identity and lifecycle canon

SmartResponsor uses `VendorEntity` as the business root. Its PostgreSQL primary key is the canonical cross-system identity and is shared one-to-one with `VendorSecurityEntity`. Objecting does not add a parallel tenant identity.

The audit and soft-delete packs use the lifecycle vocabulary `created`, `modified`, and `deleted`. Their `*By` fields carry the canonical Vendor identity as an opaque scalar. Generic tenant, organization, owner, and object-scope ownership fields are outside Objecting responsibility.

See `docs/architecture/objecting-vendor-identity-canon.md`.

## Runtime ownership

Backend components remain responsible for their own:

- Entity classes
- Doctrine migrations
- repositories
- forms
- DTOs
- controllers
- services
- business validation

Objecting is installed only as a reusable dependency for object field-pack primitives.

## Profiles are declarations, not god traits

Objecting exposes named field-pack profiles such as `object_baseline`, `object_content`, `object_lifecycle`, `object_security`, and `object_localized`. Profiles are machine-readable selections for migration, documentation, and audits; they do not replace explicit entity composition and do not justify a universal foundation trait.


## Backend migration contract

Backend components should declare their Objecting selection in a local consumer manifest before removing local boilerplate traits. The active example is `resources/consumer/object-field-packs.example.yaml`.

Objecting also ships title-alias profiles for common cases such as person, content, product, address, and organization naming. These profiles standardize the mapping from `firstTitle` / `middleTitle` / `lastTitle` without moving DTO, Form, OpenAPI, or database ownership away from the backend component.


## Consumer contract resolution

Backend components may declare field packs directly or through a named profile. Objecting resolves that declaration with `ObjectFieldPackConsumerContractResolver` into an `ObjectResolvedFieldPackConsumerContract`. The resolved contract is the migration/audit surface: it expands profile packs, removes duplicates, requires a non-empty effective selection, and prevents title aliases unless `object_title` is part of the resolved field packs.

## Symfony package surface

Objecting ships `App\Objecting\ObjectBundle` for Symfony consumers that want service registration and interface aliases. Backend components that use Objecting field packs should depend on Objecting directly, even when a host application also installs the component graph.

The bundle registers Objecting services only. It does not move consumer Entity, Migration, DTO, Form, Controller, Route, OpenAPI, or business-service ownership away from the backend component.

See `docs/integration/symfony-package-installation.md` for the consumer installation contract.

## Package quality gates

Objecting now includes a separate package-surface check for installability and Symfony service wiring:

```bash
composer test:quality
```

The package-surface check ensures that `ObjectExtension` owns runtime package parameters, `config/services.yaml` does not reset them to `null`, and mirror service-interface aliases stay registered for consumer components.



## Backend migration readiness

Objecting now exposes a package-level backend migration readiness gate for components that want to replace local boilerplate with Objecting field packs.

Baseline readiness requires these system packs:

- `object_identity`
- `object_audit`
- `object_title`

Run:

```bash
composer test:migration-readiness
```

The readiness gate validates the consumer contract only. Backend components still own their Entity, Doctrine migrations, DTO, Controller, Form, Repository, Service, and runtime behavior.


## Objecting backend adoption manifest

Wave 8 adds `resources/consumer/object-backend-adoption.example.yaml` as the canonical machine-readable adoption handshake for backend components. It records component namespace, business stem, entity class, table prefix, Objecting field-pack profile, effective packs, title alias profile, and optional Exposing mirror path.


## Backend handoff contract

Wave 9 adds `resources/consumer/object-backend-handoff.example.yaml` as the consumer-facing handoff checklist for backend components that are ready to adopt Objecting. The handoff contract records the Objecting dependency, backend-scoped manifest paths, required quality gates, required composer scripts, optional Exposing contract path, and standalone readiness.

Run:

```bash
composer test:backend-handoff
```

This gate validates handoff shape only. Backend components still own Entity composition, Doctrine migrations, DTOs, Forms, Controllers, Repositories, Services, fixtures, tests, and runtime behavior.


## Objecting release readiness

Wave 10 adds `resources/release/objecting-release-manifest.example.yaml` as the package-level release handoff for Objecting itself. It records the active package identity, cumulative/touched artifacts, quality gates, required composer scripts, and consumer contract examples that backend components rely on.

Run:

```bash
composer test:release-readiness
composer test:quality
```

This gate keeps Objecting positioned as a legacy-free system field-pack foundation, not a host app, API layer, CRUD framework, or god entity.


## Objecting release closure

Wave 11 adds `resources/release/objecting-release-closure.example.yaml` as the package-level closure marker for this Objecting track. It records the final Objecting boundary before backend migration waves begin: Objecting remains a legacy-free field-pack foundation; backend components remain runtime owners; Exposing remains the separate API/OpenAPI contract surface.

Run:

```bash
composer test:release-closure
composer test:quality
```

The closure gate verifies package identity, release artifacts, consumer contracts, canonical `object_title` support, legacy-free status, backend runtime ownership, and the next tracks for backend migration plus Exposing API contract work.


## Backend adoption packet

Wave 12 adds `resources/consumer/object-backend-adoption-packet.example.yaml` as the single machine-readable bridge from Objecting release closure into backend migration waves. It binds the local field-pack contract, migration readiness manifest, adoption manifest, handoff manifest, release closure marker, required baseline packs, quality gates, and optional Exposing contract path.

Run:

```bash
composer test:backend-adoption-packet
composer test:quality
```

This packet remains a contract surface only. Backend components still own runtime code and database migrations; Exposing remains the separate API/OpenAPI contract repository.


## Embeddable initialization contract

Wave 13 hardens every active `Object*EmbeddableTrait` with a lazy private embeddable accessor. Consumer entities may still call `initializeObject*()` explicitly when they need seeded values, but Objecting trait methods are now safe on newly constructed entities before constructor boilerplate has been added.

Run:

```bash
composer test:embeddable-initialization
composer test:quality
```

This remains a field-pack ergonomics rule only. Backend components still own Entity constructors, Doctrine migrations, and runtime behavior.


## Wave 15 schema mirror contract

Objecting now publishes a schema mirror contract for backend consumers. Backend components remain Doctrine migration owners, Objecting owns reusable `object_*` system columns, and Exposing owns API-visible schema mirrors.

Run:

```bash
composer test:schema-mirror
```

## Objecting Exposing bridge

Objecting publishes `resources/consumer/object-exposing-bridge.example.yaml` as the Objecting-side handoff for the future Exposing repository. The bridge is informational: Objecting owns field packs, backend components own runtime, and Exposing owns OpenAPI/API contracts.

Run `composer test:exposing-bridge` or `composer test:quality`.


## Backend import contract

Objecting publishes a backend import contract at `resources/consumer/object-backend-import.example.yaml`. It is an informational migration checklist for backend components that adopt Objecting field packs while keeping runtime ownership in the backend component.

Run `composer test:backend-import` or `composer test:quality` to validate the contract surface.


## Objecting RC stabilization

Wave 18 adds `resources/release/objecting-rc-stabilization.example.yaml` as the final stabilization surface before RC marker creation. It ties together release closure, backend import, adoption packet, Exposing bridge, schema mirror, and Doctrine mapping contracts without adding new runtime ownership.

Run `composer test:rc-stabilization` or `composer test:quality`.

The next Objecting wave should create the RC marker and should not expand Objecting beyond a system field-pack foundation.


## Platform constraints

Objecting targets PHP `^8.4` and Symfony 8 only. The current package dependencies use a Symfony 8 minor floor, while Symfony 7 and mixed Symfony 7/8 constraints such as `^7.0 || ^8.0` remain forbidden.

Run:

```bash
composer test:platform-constraints
composer test:quality
```

## Objecting RC1

Wave 19 adds `resources/release/objecting-rc1.example.yaml` as the RC marker for Objecting. RC1 is the dependency baseline for backend component migrations; it does not add runtime ownership or fold Exposing into Objecting.

Run:

```bash
composer test:rc
composer test:quality
```

After RC1, the next tracks are backend component migration and the separate Exposing API contract repository.


## Wave 21 systemic field packs

Objecting includes `object_state`, `object_source`, and `object_fingerprint` as canonical systemic field packs. `id` remains backend-owned, while `name`, `title`, and `description` remain aliases of `object_title`.


## Wave 22 title-alias hardening

Objecting treats `name`, `title`, `description`, `shortDescription`, `label`, and `displayName` as business aliases of `object_title` rather than separate system field packs. Canonical storage remains `first_title`, `middle_title`, and `last_title` for consumers that adopt `object_title`.

`priority` and `visibility` are intentionally deferred after the sibling workspace audit because their semantics vary by component. They must not be added as `object_priority` or `object_visibility` until a later focused decision.


## Objecting wave 23 backend clone cleanup

Wave 23 adds an Objecting-side backend clone-cleanup contract for the first sibling cleanup pilot. It identifies Addressing and Taxating as the initial components with local `Object*` clone surfaces, but it does not modify sibling repositories. Backend cleanup remains a later touched-file migration wave.

Run:

```bash
composer test:backend-clone-cleanup
composer test:quality
```


## Objecting wave 24 backend migration command packet

Wave 24 adds `resources/consumer/object-backend-migration-command.example.yaml` as the Codex-ready command packet for the first real sibling backend migration wave. It locks Objecting as the dependency baseline, keeps Exposing out of the backend migration wave, and targets Addressing/Taxating as the first clone-cleanup pilot.

Run:

```bash
composer test:backend-migration-command
composer test:quality
```


## Objecting RC2 baseline

`objecting_rc2` is the active dependency baseline after the systemic field-pack audit and backend migration command packet.

RC2 includes `object_state`, `object_source`, and `object_fingerprint` in addition to the RC1 foundation. It keeps `object_title` as the canonical storage for business `name`, `title`, `description`, `label`, and `displayName` aliases. It does not introduce `object_id`, `object_name`, `object_description`, `object_priority`, or `object_visibility`.

Run:

```bash
composer test:rc2
composer test:quality
```


## Sibling pilot migration handoff

Objecting RC2 now publishes `resources/consumer/object-sibling-pilot-migration-handoff.example.yaml` as the final Objecting-side handoff before the first real sibling backend migration wave. It locks Objecting as the dependency baseline, keeps Exposing out of the migration wave, and declares `Addressing` and `Taxating` as the first pilot components.

Run:

```bash
composer test:sibling-pilot-migration-handoff
composer test:quality
```
