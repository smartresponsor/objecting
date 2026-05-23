# Objecting Wave 3 — Title Alias and Consumer Contract

This wave continues from `objecting_wave2_field_pack_profiles_cumulative.zip`.

## Added

- `ObjectFieldPackProfileName` constants for profile-name discipline.
- `ObjectFieldPackConsumerContract` as typed consumer-side declaration value object.
- `ObjectTitleAliasProfileName`, `ObjectTitleAliasProfile`, and `ObjectTitleAliasProfileRegistry`.
- Mirror interface for the title-alias profile registry.
- Machine-readable title-alias profile resources.
- Backend migration contract documentation and expanded consumer example.

## Canonical result

Objecting now owns not only the `object_title` field pack, but also reusable title-alias profile names for person/content/product/address/organization cases. Backend components still own final DTOs, forms, APIs, migrations, and local labels.
