# Objecting Symfony package installation

Objecting is installed by backend components that use Objecting field-pack primitives.
The host application may receive it transitively through those backend components, but each standalone backend component must declare the dependency for itself.

## Composer dependency

```json
{
  "require": {
    "objecting/object": "*"
  }
}
```

During local monorepo development the consumer may use a path repository. The dependency must still be declared by the consumer component, not only by the host application.

## Bundle registration

For Symfony applications that do not auto-register bundles through recipes, register the bundle explicitly:

```php
return [
    App\Objecting\ObjectBundle::class => ['all' => true],
];
```

The bundle registers Objecting services and service-interface aliases. It does not register consumer entities, migrations, controllers, routes, DTOs, or API contracts.

## Runtime boundary

Objecting provides reusable field-pack primitives only:

- embeddables
- entity traits
- entity interfaces
- value objects
- field-pack registries
- title alias utilities
- consumer contract resolver

Consumer backend components remain the owners of Doctrine entities, migrations, repositories, forms, DTOs, controllers, services, and business validation.

## Doctrine ownership

Objecting embeddables are referenced by consumer entities through PHP classes. Consumer components own the actual entity classes and migrations that persist the embedded columns.

Objecting does not ship consumer Doctrine mappings or migrations.

## Quality gate

Before consuming a new Objecting package wave, run:

```bash
composer dump-autoload
composer test:quality
```

The quality gate confirms that the Symfony bundle, extension, services file, mirror-interface aliases, field-pack manifests, and title-alias manifests stay aligned.

