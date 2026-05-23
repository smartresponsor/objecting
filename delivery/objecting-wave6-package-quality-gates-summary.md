# Objecting wave 6 package quality gates

Wave 6 hardens the installable Symfony package surface without adding runtime ownership over backend components.

## Changed

- Removed `null` Objecting parameters from `config/services.yaml`; `ObjectExtension` remains the owner of runtime package paths.
- Added `ObjectPackageSurface` as a machine-readable description of the package surface.
- Added `tools/test/objecting_package_surface_check.php`.
- Added `composer test:package-surface` and `composer test:quality`.
- Added documentation for package quality gates.
- Strengthened the structural canon report with package-surface checks.

## Canon preserved

Objecting remains a field-pack foundation. It does not own consumer Entity, Migration, DTO, Form, Controller, Route, API, OpenAPI, or business-service layers.
