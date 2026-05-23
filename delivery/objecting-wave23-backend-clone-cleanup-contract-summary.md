# Objecting Wave 23 — Backend Clone Cleanup Contract

Objecting wave 23 adds an Objecting-side backend clone-cleanup contract for the first sibling cleanup pilot.

## Added

- `ObjectBackendCloneCleanupContract`
- `ObjectBackendCloneCleanupReport`
- `ObjectBackendCloneCleanupContractReporter` + mirror interface
- `resources/consumer/object-backend-clone-cleanup.example.yaml`
- `docs/integration/objecting-backend-clone-cleanup-contract.md`
- `tools/test/objecting_backend_clone_cleanup_contract_check.php`
- PHPUnit coverage for the reporter

## Scope

This wave does not modify sibling repositories. It documents Addressing and Taxating as the first cleanup pilot candidates because the workspace audit found local `Object*` clone surfaces there.

## Guardrails

- touched-files only
- cumulative archive is backup/reference only
- destructive repository cleanup remains forbidden
- backend components keep runtime and Doctrine migration ownership
