# Objecting release closure

Objecting release closure is the final package-level handoff marker before backend components begin adopting Objecting as a dependency.

It closes Objecting as a Symfony-oriented system field-pack foundation, not as a host application, API framework, CRUD framework, or god entity.

Canonical closure example:

```text
resources/release/objecting-release-closure.example.yaml
```

Run:

```bash
composer test:release-closure
composer test:quality
```

The closure contract records the active cumulative and touched artifacts, required quality gates, package release artifacts, consumer contracts, and the next two tracks:

```text
backend_component_migration
exposing_api_contract
```

## Boundary

Objecting owns:

```text
object_* field-pack embeddables
object_* field-pack traits
object_* entity interfaces
field-pack manifests
title-alias profiles
consumer/adoption/handoff examples
package/release gates
```

Backend components own:

```text
Entity
Doctrine migrations
DTO
Form
Controller
Repository
Service
fixtures
tests
runtime behavior
```

Exposing owns:

```text
OpenAPI contracts
API manifests
schema mirrors
DTO maps
API profiles
```

The closure gate exists so future backend migration waves can depend on Objecting without reopening old legacy, demo, API, Ontology, SDK, or god-object responsibilities.


## Wave 12 adoption packet bridge

The release closure now includes `resources/consumer/object-backend-adoption-packet.example.yaml` as a consumer contract. This keeps Objecting closed as a field-pack foundation while giving backend migrations one stable packet that references local Objecting manifests, required gates, and the optional Exposing contract path.

## Wave 16 Exposing bridge closure

`objecting_wave16_exposing_bridge_contract` adds the Objecting-side bridge for the future Exposing repository. It exports machine-readable references to field-pack, Doctrine mapping, schema mirror, and backend adoption packet files while keeping Objecting outside API ownership.

Run `composer test:exposing-bridge` or `composer test:quality`.
