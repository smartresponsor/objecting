# Objecting package quality gates

Objecting must remain an installable Symfony-oriented library and not become a host application, API layer, CRUD framework, or persistence owner for consumer components.

## Required checks

Run these checks before shipping a new Objecting wave:

```bash
composer dump-autoload
composer test:quality
```

`composer test:quality` runs:

```bash
php tools/test/objecting_canon_check.php
php tools/test/objecting_package_surface_check.php
```

## Package-surface rules

- `composer.json` must keep `objecting/object` as a library package.
- PSR-4 autoload must stay `App\Objecting\` to `src/`.
- `ObjectBundle` and `ObjectExtension` are the Symfony package surface.
- `ObjectExtension` owns runtime parameters such as `objecting.package_dir` and manifest paths.
- `config/services.yaml` must not reset those parameters to `null`.
- Service aliases must point from mirror interfaces to concrete Objecting services.
- Backend components keep their own Entity, Migration, Controller, DTO, Form, Route, OpenAPI, and business-service ownership.

## Consumer expectation

A backend component should depend on Objecting directly when it uses Objecting field packs. The host application may also install the component graph, but the component must remain standalone-testable.


## Backend migration readiness gate

Wave 7 adds a package-level readiness gate for backend components that will consume Objecting field packs:

```bash
composer test:migration-readiness
```

This gate checks the Objecting-owned migration readiness surface, including the reporter, mirror interface, readiness report value object, example YAML, and baseline identity/audit/title rule.


## Objecting backend adoption manifest

Wave 8 adds `resources/consumer/object-backend-adoption.example.yaml` as the canonical machine-readable adoption handshake for backend components. It records component namespace, business stem, entity class, table prefix, Objecting field-pack profile, effective packs, title alias profile, and optional Exposing mirror path.


## Backend handoff gate

`composer test:quality` also includes:

```bash
composer test:backend-handoff
```

The handoff gate validates `resources/consumer/object-backend-handoff.example.yaml` and the corresponding reporter/service-interface surface. It prepares backend components to adopt Objecting without transferring runtime ownership away from the backend component.


## Release readiness gate

`composer test:quality` also runs `test:release-readiness`. The release gate validates the Objecting package handoff manifest, release artifact names, consumer-contract examples, service-interface alias, and composer script surface.


## Backend adoption packet gate

`composer test:quality` also includes:

```bash
composer test:backend-adoption-packet
```

The adoption packet gate validates the Objecting-owned packet that backend components can copy into their own `resources/objecting/<BusinessStem>/` area before migration. The packet binds field-pack selection, readiness, adoption, handoff, Objecting release closure, and optional Exposing path without moving runtime ownership away from the backend component.


## Embeddable initialization gate

`composer test:quality` also includes:

```bash
composer test:embeddable-initialization
```

This gate checks that every active `Object*EmbeddableTrait` has both explicit `initializeObject*()` support and a private lazy helper that guards uninitialized typed embeddable properties. Public trait methods should call the helper rather than dereferencing the typed property directly.


## Schema mirror gate

`composer test:schema-mirror` validates the consumer-facing schema mirror contract. It ensures that backend migrations remain in backend components, Objecting owns `object_*` system columns, and Exposing remains the owner of API-visible schema mirrors.

- `composer test:exposing-bridge` validates the Objecting-side contract that future Exposing consumers can read without moving API ownership into Objecting.
