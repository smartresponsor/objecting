# Objecting wave 19 — RC marker

Wave 19 creates the Objecting RC1 marker.

## Scope

- Adds `resources/release/objecting-rc1.example.yaml`.
- Adds RC marker value objects, reporter, mirror interface, PHPUnit test, and gate.
- Adds `composer test:rc` / `composer test:rc1`.
- Keeps Objecting as a field-pack foundation dependency.
- Keeps backend components as runtime owners.
- Keeps Exposing as the separate API/OpenAPI contract track.

## Gates

```bash
composer dump-autoload
composer test:quality
composer test:rc
php tools/test/objecting_rc1_check.php
```
