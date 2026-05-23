# Objecting wave 5 — Symfony package surface

Wave 5 keeps Objecting as a system field-pack foundation and makes it easier for backend components to install predictably.

## Added

- `src/ObjectBundle.php`
- `src/DependencyInjection/ObjectExtension.php`
- `config/services.yaml`
- `docs/integration/symfony-package-installation.md`

## Updated

- `composer.json` now declares the Symfony package dependencies needed by the bundle/extension/service config surface.
- `README.md` now documents the Symfony package boundary.
- `tools/inspection/ObjectingStructuralCanonStatusReport.php` now checks package surface files and service-interface aliases.

## Boundary

Objecting still does not own consumer entities, migrations, controllers, routes, DTOs, forms, API contracts, or business services. Backend components own runtime implementation. Objecting owns reusable object field-pack primitives and their service helpers.
