# Objecting backend handoff contract

The backend handoff contract is the consumer-facing checklist used before a backend component starts replacing local boilerplate with Objecting field packs.

It is intentionally not a runtime owner transfer. The backend component remains responsible for Entity classes, Doctrine migrations, DTOs, Forms, Controllers, Repositories, Services, fixtures, tests, and business behavior.

## Canonical file

```text
resources/consumer/object-backend-handoff.example.yaml
```

Consumer components should copy the shape into their own repository under a business-stem scoped path:

```text
resources/objecting/<BusinessStem>/object-backend-handoff.yaml
```

For example:

```text
resources/objecting/Page/object-backend-handoff.yaml
```

## Required handoff content

The manifest records:

```text
component
business_stem
namespace
package.name
package.constraint
backend_project_root
objecting_paths.adoption_manifest
objecting_paths.readiness_manifest
quality_gates
required_composer_scripts
optional Exposing contract path
standalone_ready
```

The required baseline quality gates are:

```text
composer dump-autoload
composer test:quality
```

The required composer scripts are:

```text
test:canon
test:quality
```

## Ownership after handoff

Objecting owns:

```text
system field-pack vocabulary
Object*Embeddable classes
Object*EmbeddableTrait helpers
Object*Interface contracts
field-pack manifests
title-alias profiles
adoption/readiness/handoff validation helpers
```

The backend component owns:

```text
Entity composition
Doctrine table/migration ownership
DTO/Form/API exposure decisions
Controller and Service runtime behavior
component-specific tests and fixtures
```

Exposing may mirror the manifest later, but Exposing does not replace backend runtime ownership.
