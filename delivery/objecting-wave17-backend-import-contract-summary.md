# Objecting wave 17 — backend import contract

This wave adds a backend import contract that lets backend components adopt Objecting field packs through one stable, machine-readable checklist.

Objecting remains a system field-pack foundation. Backend components remain runtime owners. Exposing remains the future API contract owner.

## Added

- ObjectBackendImportContract
- ObjectBackendImportReport
- ObjectBackendImportContractReporter and mirror interface
- object-backend-import.example.yaml
- objecting_backend_import_contract_check.php
- docs/integration/objecting-backend-import-contract.md

## Quality

- composer test:quality now includes test:backend-import
- Release closure now targets objecting_wave17_backend_import_contract
