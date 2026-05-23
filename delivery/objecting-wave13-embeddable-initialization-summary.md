# Objecting wave 13 — embeddable initialization hardening

Wave 13 keeps Objecting as a system field-pack foundation and hardens consumer ergonomics for backend migrations.

## Added

- Lazy initialization helpers in every active `Object*EmbeddableTrait`.
- `docs/integration/objecting-embeddable-initialization.md`.
- `tools/test/objecting_embeddable_initialization_check.php`.
- `composer test:embeddable-initialization` and inclusion in `composer test:quality`.

## Rule

Consumer entities may still call `initializeObject*()` explicitly for seeded values, but public Objecting trait methods must be safe before constructor boilerplate exists.
