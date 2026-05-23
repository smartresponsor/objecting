# Objecting backend adoption packet

`resources/consumer/object-backend-adoption-packet.example.yaml` is the single consumer-facing packet that a backend component can mirror before starting an Objecting migration wave.

The packet does not move runtime ownership into Objecting. It only binds the existing Objecting consumer contracts into one machine-readable handoff surface.

## What the packet connects

- local field-pack contract path;
- backend migration readiness manifest path;
- backend adoption manifest path;
- backend handoff manifest path;
- Objecting release closure manifest path;
- required baseline system packs;
- quality gates and required composer scripts;
- optional Exposing contract path.

## Required baseline field packs

Every backend object consumer must declare these packs before migration work begins:

```text
object_identity
object_audit
object_title
```

`object_title` is required because `firstTitle`, `middleTitle`, and `lastTitle` are canonical system fields. Domain-specific labels such as first name, title, short description, or description are aliases owned by the backend component and its DTO/Form/API layers.

## Gate

Run:

```bash
composer test:backend-adoption-packet
composer test:quality
```

The gate validates Objecting's package-level packet example and reporter surface. A backend component may copy the same shape under `resources/objecting/<BusinessStem>/` and then run its own component-level checks.
