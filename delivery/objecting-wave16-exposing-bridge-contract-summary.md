# Objecting wave 16 — Exposing bridge contract

Wave 16 adds the Objecting-side bridge for the future Exposing repository.

## Scope

- Adds `ObjectExposingBridgeContract` and report/value objects.
- Adds `ObjectExposingBridgeContractReporter` with mirror interface.
- Adds `resources/consumer/object-exposing-bridge.example.yaml`.
- Adds `tools/test/objecting_exposing_bridge_contract_check.php`.
- Registers `composer test:exposing-bridge` and includes it in `composer test:quality`.
- Updates package surface and release closure markers to `objecting_wave16_exposing_bridge_contract`.

## Boundary

Objecting exports field-pack, Doctrine mapping, schema mirror, and backend adoption packet references. Backend components remain runtime owners. Exposing remains the API/OpenAPI contract owner.
