# Objecting Exposing Bridge Contract

Objecting publishes a small Exposing bridge contract so a future `Exposing` repository can discover the backend object's field-pack, Doctrine mapping, schema mirror, and adoption packet references without making Objecting an API framework.

The bridge is intentionally informational:

- Objecting owns reusable `object_*` field-pack declarations.
- Backend components own Entity, Doctrine migrations, DTO, Form, Controller, Repository, Service, fixtures, tests, and runtime behavior.
- Exposing owns OpenAPI contracts, API profiles, examples, and API-visible schema mirrors.

The canonical example is `resources/consumer/object-exposing-bridge.example.yaml`.

Required baseline packs are always:

- `object_identity`
- `object_audit`
- `object_title`

Run `composer test:exposing-bridge` or `composer test:quality`.
