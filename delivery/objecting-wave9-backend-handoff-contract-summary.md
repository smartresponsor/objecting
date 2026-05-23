# Objecting Wave 9 — Backend Handoff Contract

Wave 9 adds the consumer-facing backend handoff contract for components that are ready to adopt Objecting field packs.

## Added

- `ObjectBackendHandoffManifest`
- `ObjectBackendHandoffReport`
- `ObjectBackendHandoffManifestReporter`
- `ObjectBackendHandoffManifestReporterInterface`
- `resources/consumer/object-backend-handoff.example.yaml`
- `docs/integration/objecting-backend-handoff-contract.md`
- `tools/test/objecting_backend_handoff_contract_check.php`
- `composer test:backend-handoff`

## Reinforced

- `composer test:quality` now includes the backend handoff gate.
- `ObjectPackageSurface` exposes handoff example/doc/check constants.
- `config/services.yaml` registers the mirror interface alias for the handoff reporter.
- Structural/package checks know the handoff surface.

## Ownership canon

The backend handoff contract does not transfer runtime ownership away from backend components. Backend components keep Entity, Doctrine migrations, DTO, Form, Controller, Repository, Service, tests, fixtures, and runtime behavior. Objecting owns only system field-pack primitives, manifests, title alias profiles, and validation helpers.
