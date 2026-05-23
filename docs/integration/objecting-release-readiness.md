# Objecting release readiness

Objecting release readiness is the package-level handoff gate for the Objecting repository itself. It is different from backend adoption gates: backend components own their Entity composition, migrations, DTOs, controllers, repositories, forms, and business services.

The release manifest records the package identity, active artifacts, quality gates, composer scripts, and consumer contract examples that must remain available before Objecting is used as a dependency by backend components.

Canonical manifest example:

```text
resources/release/objecting-release-manifest.example.yaml
```

Run:

```bash
composer test:release-readiness
composer test:quality
```

Release readiness does not make Objecting a host application, API framework, CRUD framework, or god entity. The package remains a Symfony-oriented field-pack foundation with Object-prefixed classes under the `App\Objecting\` namespace.


## Closure handoff

Release readiness is followed by the release closure gate:

```bash
composer test:release-closure
```

The closure gate records that Objecting is ready to be consumed by backend migration waves while Exposing remains a separate API/OpenAPI contract repository.
