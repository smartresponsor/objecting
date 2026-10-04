# Objecting production diagnostics

Objecting diagnostics are explicit package reports for manifest, compatibility, lifecycle, schema, alias, and consumer-inspection failures. They are not routine entity telemetry.

## Logging boundary

- Embeddables and embeddable traits have no logger dependency.
- Normal reads, writes, Doctrine hydration, and lifecycle transitions are not logged.
- Consumers may forward diagnostic reports to their PSR-3 logger or observability pipeline.
- Objecting does not own logging sinks, Prometheus exporters, tracing backends, or alert routing.

## Stable event identifiers

Stable identifiers are defined by `ObjectDiagnosticEventId` and cover invalid manifests, unknown profiles or packs, lifecycle invariant violations, schema mismatches, title alias conflicts, duplicate local system fields, and deprecated public API use.

## Machine-readable report

`ObjectDiagnosticReporter` validates the event identifier and produces `ObjectDiagnosticReport`. The report implements `JsonSerializable` and emits:

```json
{
  "event_id": "objecting.schema.mismatch",
  "message": "Consumer schema does not match the Objecting manifest.",
  "context": {
    "component": "Addressing"
  }
}
```

Diagnostic context must not contain credentials, tokens, personal data, or full entity payloads.

## Gate

```bash
composer test:production-diagnostics
composer test:quality
```
