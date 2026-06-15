# Objecting platform constraints

Objecting is a Symfony 8 / PHP 8.4 package baseline. The Symfony minor floor may advance within major version 8 without changing the architectural baseline.

## Required composer constraints

```json
{
  "require": {
    "php": "^8.4",
    "symfony/config": "^8.0",
    "symfony/dependency-injection": "^8.0",
    "symfony/http-kernel": "^8.0",
    "symfony/uid": "^8.0",
    "symfony/yaml": "^8.0"
  },
  "extra": {
    "symfony": {
      "require": "^8.0"
    }
  }
}
```

Symfony 7 compatibility is intentionally not part of the Objecting baseline. Each Symfony dependency must use a single-major constraint such as `^8.0` or `^8.1`; mixed constraints such as `^7.0 || ^8.0` are forbidden.

## Gate

```bash
composer test:platform-constraints
php tools/test/objecting_platform_constraint_check.php
```

The platform gate is included in `composer test:quality` and must stay part of the RC checks.
