# Objecting current-snapshot quality cleanup audit

## Source boundary

- Source archive: `Objecting5_tenant_lifecycle_cleanup_full.zip`
- SHA-256: `3ef02dd71e286e2d3f6456f6108d93f8b1fa65cad98dcfd7bb8421f86855eb53`
- This cleanup uses only that archive as the current Objecting snapshot.

## Canonical decisions applied

- Objecting uses suffix-specific Symfony-oriented layers: `Reporter`, `Registry`, `Resolver`, and `Normalizer`, each with its mirrored interface layer.
- The obsolete broad `Service` / `ServiceInterface` duplicate tree is not an active compatibility surface.
- Canonical identifiers and public value-object accessors use `name`, not the accidental `nameEntity` vocabulary.
- `name` remains a supported Object title alias.
- The platform boundary is PHP `^8.4` and Symfony major version 8 only. A Symfony 8 minor floor may advance without rewriting the architectural baseline.
- Internal `Object*` types must resolve through their own namespace or an explicit import.

## Drifts removed

### 1. Broad duplicate service tree

Deleted 50 obsolete PHP files under:

- `src/Service/`
- `src/ServiceInterface/`

The active implementations already existed under their suffix-specific layers and were already used by `config/services.yaml`.

The unused `NullObjectReferenceIntegrityChecker` was removed with the obsolete tree. It was not wired into the container and returned `true` for every reference, so preserving it would have provided false integrity semantics.

### 2. `nameEntity` vocabulary corruption

The accidental token appeared in Composer checks, PHP-CS-Fixer configuration, title aliases, manifests, packets, contracts, tests, reporters, and error messages.

It was normalized to `name` throughout the active package. Notable corrections include:

- `ObjectTitleAliasMap::ALIAS_NAME = 'name'`;
- `ObjectSystemTokenDecision::TOKEN_NAME = 'name'`;
- `ObjectFieldPackManifest::name()`;
- `ObjectFieldPackProfile::name()`;
- `ObjectTitleAliasProfile::name()`;
- Composer package checks reading `composer.json[name]`;
- YAML declaration checks reading the canonical `name:` key.

No permanent `nameEntity()` compatibility aliases were retained.

### 3. Embeddable trait namespace/style drift

All active `Object*EmbeddableTrait` files now import their embeddable class explicitly and use the short class name in Doctrine attributes, typed properties, constructors, and lazy helpers.

This also makes the trait initialization gates independent from fully-qualified inline class spelling.

### 4. Symfony minor-version gate overconstraint

The package currently requires Symfony `^8.1`, while historical RC artifacts describe a Symfony `^8.0` floor. Both constraints are Symfony-8-only.

The platform gate now rejects Symfony 7 and mixed-major constraints while allowing a canonical single-major Symfony 8 constraint such as `^8.0` or `^8.1`.

### 5. Hidden internal type-resolution defects

Runtime smoke testing exposed that every report class referenced its corresponding Manifest, Contract, or Packet without importing it. PHP therefore resolved the type inside `App\\Objecting\\Report`, producing nonexistent classes such as:

- `App\\Objecting\\Report\\ObjectPlatformConstraintManifest`
- `App\\Objecting\\Report\\ObjectBackendAdoptionManifest`
- `App\\Objecting\\Report\\ObjectDoctrineMappingContract`

Explicit imports were added to all affected reports. The same audit found and fixed missing imports in contracts, manifests, the migration packet, and the package surface.

A new gate was added:

```text
composer test:internal-types
```

It rejects internal `Object*` references that rely on accidental same-namespace resolution instead of an explicit import.

## Validation

Passed:

- 201 PHP files linted successfully;
- all JSON and YAML files parsed successfully;
- all 26 standalone package gates passed;
- 145 internal symbols loaded through a local PSR-4 smoke autoloader;
- two external-dependent Symfony classes were intentionally skipped in the no-vendor sandbox;
- 16 field packs loaded from `ObjectFieldPackRegistry`;
- `object_title_product` resolves `firstTitle` to `name`;
- a Symfony `^8.1` platform manifest reports ready while the Flex major constraint remains Symfony 8;
- no active `nameEntity` or old Service namespace references remain.

`composer test`, PHPUnit, and PHPStan could not be executed because Composer and `vendor/` are unavailable in the sandbox. No claim is made that those three commands were run.

## Change summary

Relative to the supplied source archive:

- 50 files deleted;
- 1 new quality-gate file added;
- 92 existing files modified.

The large modified count is primarily the deliberate package-wide `nameEntity` to `name` semantic correction and explicit import repair.
