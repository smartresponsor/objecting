# Objecting wave 22 — title alias hardening

Wave 22 keeps title/name/description-like tokens under `object_title` aliases instead of adding new DB fields or field packs.

Added/changed:
- hardened `ObjectTitleAliasMap` with common business alias constants, duplicate alias rejection, and reverse lookup helpers;
- added `object_title_label` and `object_title_display` alias profiles;
- added `ObjectSystemTokenDecision` for explicit token disposition;
- added title alias governance example;
- added `objecting_title_alias_hardening_check.php` and composer `test:title-alias-hardening`;
- documented that `priority` and `visibility` remain deferred and must not become field packs yet.

Forbidden packs still absent:
- `object_id`
- `object_name`
- `object_description`
- `object_priority`
- `object_visibility`

Checks run:
- all `tools/test/objecting_*_check.php` passed;
- `php -l` over `src`, `tests`, and `tools` PHP files passed.
