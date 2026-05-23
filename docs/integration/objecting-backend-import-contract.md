# Objecting backend import contract

The backend import contract is the Objecting-side packet a backend component can use before migrating an entity to Objecting field packs.

It does not replace backend runtime files. The backend component remains owner of Entity, Doctrine migrations, DTO, Form, Controller, Repository, Service, fixtures, tests, and runtime behavior.

## Required baseline

Every backend object import must include:

- `object_identity`
- `object_audit`
- `object_title`

The title pack is canonical. Domain names such as `firstName`, `middleName`, `lastName`, `title`, `shortDescription`, or `description` are aliases over `firstTitle`, `middleTitle`, and `lastTitle`.

## Import files

The example import packet is published at:

```text
resources/consumer/object-backend-import.example.yaml
```

A backend component should mirror it into its own repository, typically under:

```text
resources/objecting/<BusinessStem>/object-backend-import.yaml
```

## Quality gate

Run:

```bash
composer test:backend-import
```

or as part of the full quality surface:

```bash
composer test:quality
```

The import contract is informational. It gives backend migrations a stable checklist and must not become a runtime owner or an API owner.
