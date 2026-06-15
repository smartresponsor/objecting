# Objecting Canon

Objecting is the object system field-pack foundation for Smart Responsor backend components.

## What Objecting is

Objecting provides small, composable field packs for recurring system concerns. Backend entities choose the packs they need instead of extending a universal god entity.

## What Objecting is not

Objecting is not responsible for API contracts, OpenAPI generation, application routing, gateway behavior, CRUD screens, or host runtime orchestration. Those responsibilities belong to other components, especially Exposing for API contracts.

## Naming

The component namespace is `App\Objecting`.

The business stem is `Object`.

Therefore active classes use the `Object*` prefix, for example:

- `ObjectTitleEmbeddable`
- `ObjectTitleEmbeddableTrait`
- `ObjectTitledInterface`
- `ObjectTitleAliasResolver`

## Field-pack shape

A canonical field pack should have these surfaces when applicable:

```text
Embeddable       Doctrine field grouping
EntityTrait      ergonomic consumer entity methods
EntityInterface  capability contract for consumer entities
Manifest         machine-readable field-pack declaration
Reporter         report construction
ReporterInterface mirrored reporter contract
Registry         canonical lookup surface
RegistryInterface mirrored registry contract
Resolver         deterministic contract resolution
ResolverInterface mirrored resolver contract
Normalizer       canonical value normalization
NormalizerInterface mirrored normalizer contract
```

## No legacy retention

Legacy files are not preserved unless they are transformed into the active Objecting canon and used by the clean component. Historical API, ontology, reaction, SDK, demo runtime, duplicate roots, `src/src`, and `* (2).php` files are not active Objecting code.

## No universal foundation trait

Objecting does not provide an active `ObjectFoundationEmbeddableTrait` or similar full-pack shortcut. A backend entity must choose the system packs that fit its responsibility. Named profiles are allowed as manifest/documentation aids, but they remain declarations rather than inheritance shortcuts.
