# Objecting Backend Migration Readiness

Objecting backend migration readiness is the package-level gate used before a backend component starts replacing local boilerplate with Objecting system field packs.

The readiness gate does not own the backend Entity, migration, DTO, controller, form, repository, service, or runtime behavior. It only verifies that the component has declared an Objecting consumer contract that can be resolved consistently.

## Baseline field packs

Every object consumer must resolve to at least these baseline system field packs:

- `object_identity`
- `object_audit`
- `object_title`

`object_title` is mandatory because Objecting defines the universal three-field object title standard:

- `firstTitle`
- `middleTitle`
- `lastTitle`

Domain-specific names are aliases over these canonical fields. For example, a content entity may expose `title`, `shortDescription`, and `description`, while a person entity may expose `firstName`, `middleName`, and `lastName`.

## Readiness report

The readiness reporter resolves the consumer contract and returns:

- status: `ready` or `blocked`
- explicit field packs
- effective field packs
- required baseline field packs
- missing baseline field packs
- title alias profile
- checks
- blocking reasons

## Backend namespace expectation

For backend migration readiness, the entity class must follow the component namespace convention:

```text
App\\<Component>\\Entity\\<BusinessStem>
```

Example:

```text
App\\Paging\\Entity\\Page
```

This does not make Objecting the owner of the entity. It only lets Objecting validate that the consumer contract points to the intended backend component.

## Example

See:

```text
resources/consumer/object-backend-migration-readiness.example.yaml
```

## Gate command

```bash
composer test:migration-readiness
```
