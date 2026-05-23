# Objecting wave 4 — contract resolution

Wave 4 keeps Objecting focused on system field-pack foundation and adds a resolver layer for backend migration contracts.

## Added

- `ObjectResolvedFieldPackConsumerContract`
- `ObjectFieldPackConsumerContractResolverInterface`
- `ObjectFieldPackConsumerContractResolver`
- `ObjectFieldPackConsumerContractResolverTest`
- contract resolution documentation

## Strengthened

- Consumer contracts now reject declaring both an inline title alias map and a named title-alias profile.
- Resolved contracts require a non-empty effective field-pack selection.
- Title aliases require `object_title` after profile expansion.
- The consumer example now distinguishes explicit, profile-based, and effective field-pack selections.

## Canonical intent

Backend components can declare `field_pack_profile` first, resolve the final field-pack list, and only then remove local boilerplate. This keeps migrations machine-checkable without reintroducing a god trait or moving runtime ownership out of the backend component.
