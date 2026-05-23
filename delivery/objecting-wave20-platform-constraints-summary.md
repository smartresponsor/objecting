# Objecting Wave 20 — Platform Constraints Correction

Wave 20 corrects the RC1 platform baseline.

## Canon

- PHP: `^8.4`
- Symfony packages: `^8.0` only
- Forbidden: Symfony `^7`, mixed `^7 || ^8`, and `^7.0 || ^8.0` constraints

## Main changes

- Updated `composer.json` Symfony package constraints from `^7.0 || ^8.0` to `^8.0`.
- Updated `extra.symfony.require` to `^8.0`.
- Added Objecting platform constraint manifest, reporter, mirror interface, PHPUnit test, and gate.
- Added `composer test:platform-constraints` and wired it into `composer test:quality`.
- Updated RC1 marker/docs to include the platform gate.

## Checks

- `php tools/test/objecting_platform_constraint_check.php`
- `php tools/test/objecting_rc1_check.php`
- all `tools/test/objecting_*_check.php`
- `php tools/inspection/ObjectingStructuralCanonStatusReport.php`
- `php -l` over all PHP files
