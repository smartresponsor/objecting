# Object Title Alias Profiles

Objecting owns the canonical object-title field pack:

```text
firstTitle
middleTitle
lastTitle
```

These are system fields. A backend component may expose them through domain-specific names by selecting a title-alias profile or by declaring an explicit local alias map.

## Canonical profiles

| Profile | firstTitle | middleTitle | lastTitle |
| --- | --- | --- | --- |
| `object_title_canonical` | `firstTitle` | `middleTitle` | `lastTitle` |
| `object_title_person` | `firstName` | `middleName` | `lastName` |
| `object_title_content` | `title` | `shortDescription` | `description` |
| `object_title_product` | `name` | `subtitle` | `description` |
| `object_title_address` | `addressLine1` | `addressLine2` | `displayLabel` |
| `object_title_organization` | `legalName` | `tradeName` | `description` |

Consumer components own final DTO field names, Symfony Form labels, API exposure, and UI wording. Objecting only provides the canonical system vocabulary and reusable alias profiles.
