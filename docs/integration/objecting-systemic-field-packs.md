# Objecting systemic field packs

Wave 21 adds four systemic field packs discovered through the sibling workspace audit.

## Added packs

- `object_state`: `active`, `enabled`, `status`
- `object_source`: `source`, `provider`, `external_id`, `source_type`
- `object_fingerprint`: `hash`, `checksum`, `algorithm`

## Explicit non-goals

- `id` remains backend-owned Doctrine primary key surface.
- `name`, `title`, and `description` remain aliases of `object_title` (`firstTitle`, `middleTitle`, `lastTitle`).
- `priority` and `visibility` are intentionally deferred until their semantics are less overloaded.
