# Object Title Field Pack

`object_title` defines the universal three-part object title surface.

## Canonical PHP fields

```text
firstTitle
middleTitle
lastTitle
```

## Default database columns

```text
object_first_title
object_middle_title
object_last_title
```

## Alias examples

```yaml
person:
  firstTitle: firstName
  middleTitle: middleName
  lastTitle: lastName

page:
  firstTitle: title
  middleTitle: shortDescription
  lastTitle: description

product:
  firstTitle: name
  middleTitle: subtitle
  lastTitle: description
```

The canonical fields belong to Objecting. Concrete aliases belong to the consuming component manifest, forms, DTOs, and API contracts.


## Alias profiles

Objecting now exposes canonical title-alias profiles under `resources/title-alias/profile/`. These profiles do not force API or form field names. They provide a shared vocabulary for backend migrations and later Exposing mirrors.
