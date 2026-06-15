# Objecting systemic field packs

Wave 21 adds four systemic field packs discovered through the sibling workspace audit.

## Added packs

- `object_state`: `object_active`, `object_enabled`, `object_status`
- `object_source`: `object_source`, `object_provider`, `object_external_id`, `object_source_type`
- `object_fingerprint`: `object_hash`, `object_checksum`, `object_algorithm`

## Explicit non-goals

- `id` remains backend-owned Doctrine primary key surface.
- `name`, `title`, and `description` remain aliases of `object_title` (`firstTitle`, `middleTitle`, `lastTitle`).
- `priority` and `visibility` are intentionally deferred until their semantics are less overloaded.
