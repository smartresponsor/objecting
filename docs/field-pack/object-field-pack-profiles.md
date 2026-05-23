# Object Field-Pack Profiles

Objecting profiles are named selections of field packs used for migration planning, documentation, and AI-agent navigation. They are not replacement base entities and not universal traits.

- `object_baseline`: identity, audit, title.
- `object_content`: identity, audit, title, publication, version.
- `object_lifecycle`: identity, audit, soft delete, lock, workflow, version.
- `object_security`: identity, audit, title, token, restriction, version.
- `object_localized`: identity, audit, title, locale, version.


## Profile-name constants

Profile names are also available through `ObjectFieldPackProfileName` so backend migration tools and audits do not depend on ad-hoc string literals.
