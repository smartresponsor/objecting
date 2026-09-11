# Objecting title-alias hardening

Objecting wave 22 keeps `object_title` as the only canonical storage surface for generic object titles.

## Canonical fields

The canonical PHP fields are:

- `firstTitle`
- `middleTitle`
- `lastTitle`

The canonical database columns are:

- `first_title`
- `middle_title`
- `last_title`

Consumer components may expose business aliases such as `name`, `title`, `description`, `shortDescription`, `label`, or `displayName`, but those aliases must not create duplicate canonical DB columns when `object_title` is adopted.

## Alias tokens

The following tokens are treated as `object_title` aliases:

- `name`
- `title`
- `description`
- `shortDescription`
- `label`
- `displayName`
- `summary`
- `subtitle`

## Deferred tokens

`priority` and `visibility` are not Objecting field packs in wave 22. They remain deferred because their semantics vary between sorting, severity, queues, publication, UI, security, and tenant exposure.

## Identity token

Plain `id` is the canonical shared Doctrine primary key supplied by `object_identity` through `ObjectIdentityEmbeddableTrait`. Objecting does not introduce an `object_id` physical column or separate pack because the canonical storage name is simply `id`.

## Forbidden wave 22 packs

Objecting must not add these packs in this wave:

- `object_id`
- `object_name`
- `object_description`
- `object_priority`
- `object_visibility`
