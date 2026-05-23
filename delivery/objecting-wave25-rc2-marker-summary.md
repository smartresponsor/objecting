# Objecting wave 25 — RC2 marker

This wave closes Objecting after the workspace audit follow-up and marks `objecting_rc2` as the active dependency baseline.

## Added

- `ObjectRc2MarkerManifest`
- `ObjectRc2MarkerReport`
- `ObjectRc2MarkerManifestReporter` and mirror interface
- `resources/release/objecting-rc2.example.yaml`
- `docs/release/objecting-rc2.md`
- `tools/test/objecting_rc2_check.php`
- `composer test:rc2`

## Baseline

RC2 includes the systemic field packs from wave 21 and the title alias hardening / backend migration command packet from waves 22–24.

## Boundary

- Objecting remains a field-pack foundation only.
- Backend components remain runtime and migration owners.
- Exposing remains the separate API/OpenAPI contract track.
- `id` remains backend-owned.
- `name`, `title`, and `description` remain `object_title` aliases.
- `priority` and `visibility` remain deferred.
