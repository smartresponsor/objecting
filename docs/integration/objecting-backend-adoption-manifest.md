# Objecting backend adoption manifest

The backend adoption manifest is the machine-readable handshake between a backend component and Objecting.

It is intentionally not an API contract and not a Doctrine migration source. It declares how a backend entity adopts Objecting field packs before the backend migration wave starts.

## Required ownership split

- Objecting owns system field packs, field-pack profiles, title alias profiles, and adoption validation.
- The backend component owns Entity, Doctrine migrations, DTO, controllers, forms, repositories, services, fixtures, tests, and runtime behavior.
- Exposing may mirror the manifest path later for API/OpenAPI/schema-contract work.

## Canonical shape

```yaml
object_backend_adoption_version: 1
component: Paging
business_stem: Page
namespace: App\\Paging
entity: App\\Paging\\Entity\\Page
table: page
standalone_ready: true
field_pack_profile: object_content
explicit_field_packs: []
effective_field_packs:
  - object_identity
  - object_audit
  - object_title
title_alias_profile: object_title_content
exposing_contract:
  owner: Exposing
  path: contract/component/Paging/Page/manifest.yaml
```

## Baseline packs

Every backend object consumer must resolve at least:

- `object_identity`
- `object_audit`
- `object_title`

`object_title` is mandatory because `firstTitle`, `middleTitle`, and `lastTitle` are canonical system title fields for every object. Business names are aliases, not replacement system fields.

## Table prefix rule

The backend table must use the business/entity prefix. For `Page`, valid examples are `page` and `page_revision`. Invalid examples are `content_page` or `object_page` unless the business stem itself is `Object`.
