# Objecting embeddable initialization

Objecting entity traits expose lazy embeddable initialization guards. A backend entity may still call `initializeObject*()` from its constructor when it wants explicit seed values, but the public trait methods must also be safe on a newly constructed entity before that constructor wiring exists.

## Canonical rule

Each active `Object*EmbeddableTrait` must keep three pieces together:

```text
private Object*Embeddable $object*
protected function initializeObject*()
private function object*Embeddable(): Object*Embeddable
```

The private accessor initializes the embeddable when the typed property has not been set yet. Public trait methods must call that private accessor instead of dereferencing the typed property directly.

## Why this belongs in Objecting

Objecting is the system field-pack foundation. It should reduce backend boilerplate, not require every backend entity to remember a long constructor checklist. Explicit initialization remains available for seeded values such as UUID, slug, audit creator, token, or canonical title fields.

## Consumer guidance

Backend components can choose one of two valid styles:

```text
Constructor-seeded:
  call initializeObjectIdentity(), initializeObjectAudit(), initializeObjectTitle(), etc.

Lazy default:
  rely on Objecting trait accessors to create default embeddables before first use.
```

Doctrine migrations and table ownership still remain in the backend component. This initialization contract only protects the reusable Objecting trait surface.
