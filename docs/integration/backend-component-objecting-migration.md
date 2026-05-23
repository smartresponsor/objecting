# Backend Component Objecting Migration Contract

A backend component that adopts Objecting should add a local Objecting consumer declaration before moving fields out of local traits.

## Required local declaration

```text
resources/objecting/<BusinessStem>/object-field-packs.yaml
```

The declaration should include:

```yaml
component: Paging
business_stem: Page
namespace: App\Paging
entity: App\Paging\Entity\Page
field_pack_profile: object_content
field_packs:
  - object_identity
  - object_audit
  - object_title
  - object_publication
  - object_version
title_alias_profile: object_title_content
title_aliases:
  firstTitle: title
  middleTitle: shortDescription
  lastTitle: description
```

## Ownership rule

Objecting supplies reusable system field packs. The backend component remains the owner of Doctrine migrations, Entity composition, DTOs, controllers, Symfony Forms, repositories, services, and business behavior.

## Migration order

1. Add the local consumer declaration.
2. Replace duplicated local system traits with Objecting field-pack traits.
3. Keep component-specific business fields in the component.
4. Regenerate or update Doctrine migrations inside the backend component.
5. Mirror the resulting API/DB shape later through Exposing.


## Objecting backend adoption manifest

Wave 8 adds `resources/consumer/object-backend-adoption.example.yaml` as the canonical machine-readable adoption handshake for backend components. It records component namespace, business stem, entity class, table prefix, Objecting field-pack profile, effective packs, title alias profile, and optional Exposing mirror path.
