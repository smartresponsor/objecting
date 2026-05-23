# Objecting wave 14 — Doctrine mapping contract

Wave 14 adds a backend-facing Doctrine mapping contract so Objecting field packs can be adopted by backend entities with an explicit, machine-readable persistence boundary.

## Added

- `ObjectDoctrineMappingContract`
- `ObjectDoctrineMappingReport`
- `ObjectDoctrineMappingContractReporter`
- `ObjectDoctrineMappingContractReporterInterface`
- `resources/consumer/object-doctrine-mapping.example.yaml`
- `docs/integration/objecting-doctrine-mapping-contract.md`
- `tools/test/objecting_doctrine_mapping_contract_check.php`
- `ObjectDoctrineMappingContractReporterTest`

## Strengthened

- `composer test:quality` now includes `test:doctrine-mapping`.
- `ObjectPackageSurface` exposes Doctrine mapping constants.
- `config/services.yaml` has the mirror interface alias for Doctrine mapping reporting.
- Release closure now points to wave 14 and includes Doctrine mapping readiness.
- Structural canon report validates the new Doctrine mapping surface.

## Boundary

Objecting still owns only reusable `object_*` field-pack primitives. Backend components remain owners of Entity classes, table names, Doctrine migrations, repositories, DTOs, forms, controllers, fixtures, tests, and runtime behavior.
