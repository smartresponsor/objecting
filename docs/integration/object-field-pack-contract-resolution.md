# Object field-pack contract resolution

Backend components may declare Objecting field packs directly, through a named profile, or through both. The resolver produces one canonical effective selection before a migration wave removes local boilerplate.

## Canonical flow

```text
ObjectFieldPackConsumerContract
  -> ObjectFieldPackConsumerContractResolver
  -> ObjectResolvedFieldPackConsumerContract
```

The unresolved contract is the local declaration supplied by a backend component. The resolved contract is the machine-checkable view used by migration audits, documentation, and future Exposing mirrors.

## Resolution rules

- `field_packs` may contain explicit packs.
- `field_pack_profile` expands to its configured packs.
- Explicit packs and profile packs are merged without duplicates.
- The resolved selection must not be empty.
- `title_alias_profile` and inline `title_aliases` require `object_title` in the resolved selection.
- Inline title alias maps and named title-alias profiles are mutually exclusive in one contract.

## Example

```yaml
component: Paging
business_stem: Page
entity: App\Paging\Entity\Page
field_pack_profile: object_content
field_packs: []
title_alias_profile: object_title_content
```

This resolves to:

```text
object_identity
object_audit
object_title
object_publication
object_version
```

The backend still owns Entity classes, Doctrine migrations, DTOs, controllers, forms, and business behavior. Objecting only resolves reusable system field-pack composition.
