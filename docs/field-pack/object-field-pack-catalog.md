# Object Field-Pack Catalog

Objecting field packs are composable. Consumer entities select the packs that match their business responsibility.

Baseline packs: `object_identity`, `object_audit`, `object_title`.

Optional packs: `object_publication`, `object_soft_delete`, `object_version`, `object_locale`, `object_token`, `object_restriction`, `object_lock`, `object_workflow`, `object_config`, `object_code`.

Each canonical pack should have an embeddable, entity trait, entity interface, and manifest. The `object_title` pack owns `firstTitle`, `middleTitle`, and `lastTitle` as canonical system fields.
