# Objecting RC1

Objecting RC1 is the dependency baseline for backend component migration waves.

## Scope

Objecting RC1 owns only system object field-pack primitives:

- object identity
- object audit
- object title (`firstTitle`, `middleTitle`, `lastTitle`)
- object publication
- object soft delete
- object version
- object locale
- object token
- object restriction
- object lock
- object workflow
- object config
- object code

Objecting RC1 does not own backend runtime behavior, API/OpenAPI contracts, Doctrine migrations, DTOs, forms, controllers, repositories, or business services.

## RC contract

The RC marker is `resources/release/objecting-rc1.example.yaml`.

The marker binds:

- release closure
- RC stabilization
- backend import contract
- backend adoption packet
- Doctrine mapping contract
- schema mirror contract
- Exposing bridge contract
- final quality gates


## Platform baseline

Objecting RC1 requires PHP `^8.4` and Symfony packages `^8.0` only. Symfony 7 constraints and mixed constraints such as `^7.0 || ^8.0` are forbidden.

The platform marker is `resources/release/objecting-platform-constraints.example.yaml`.

## Required gates

```bash
composer dump-autoload
composer test:quality
composer test:platform-constraints
composer test:rc
php tools/test/objecting_rc1_check.php
php tools/test/objecting_platform_constraint_check.php
```

## Next tracks

After RC1, Objecting work should move into consumer migration waves. Backend components should adopt Objecting through their local objecting manifests while keeping runtime ownership.

Exposing remains a separate repository/track for OpenAPI, API manifests, DTO maps, and schema mirrors.
