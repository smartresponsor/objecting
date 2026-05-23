# Backend Component Field-Pack Contract

A backend component that consumes Objecting keeps runtime ownership of its entity, migrations, forms, DTOs, controllers, services, repositories, tests, and business validation.

Objecting contributes only reusable object system primitives.

```yaml
component: Paging
business_stem: Page
entity: App\Paging\Entity\Page
field_packs:
  - object_identity
  - object_audit
  - object_title
  - object_publication
  - object_soft_delete
  - object_version
title_aliases:
  firstTitle: title
  middleTitle: shortDescription
  lastTitle: description
```


## Resolution step

Before a backend component removes local boilerplate traits, its local Objecting manifest should be resolved into an effective field-pack selection. Use `ObjectFieldPackConsumerContractResolver` to combine explicit `field_packs` with the named `field_pack_profile`. The resulting `ObjectResolvedFieldPackConsumerContract` is the canonical migration surface for audits and future Exposing mirrors.
