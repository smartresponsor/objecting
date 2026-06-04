<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$src = $root . DIRECTORY_SEPARATOR . 'src';
$errors = [];

$rules = [
    'Embeddable' => static fn (string $name, string $kind): bool => str_ends_with($name, 'Embeddable'),
    'EntityInterface' => static fn (string $name, string $kind): bool => 'interface' === $kind && str_ends_with($name, 'Interface'),
    'EntityTrait' => static fn (string $name, string $kind): bool => 'trait' === $kind && str_ends_with($name, 'Trait'),
    'Factory' => static fn (string $name, string $kind): bool => str_ends_with($name, 'Factory'),
    'Contract' => static fn (string $name, string $kind): bool => str_ends_with($name, 'Contract'),
    'Manifest' => static fn (string $name, string $kind): bool => str_ends_with($name, 'Manifest'),
    'Report' => static fn (string $name, string $kind): bool => str_ends_with($name, 'Report'),
    'Packet' => static fn (string $name, string $kind): bool => str_ends_with($name, 'Packet'),
    'Decision' => static fn (string $name, string $kind): bool => str_ends_with($name, 'Decision'),
    'Surface' => static fn (string $name, string $kind): bool => str_ends_with($name, 'Surface'),
    'Reporter' => static fn (string $name, string $kind): bool => str_ends_with($name, 'Reporter'),
    'ReporterInterface' => static fn (string $name, string $kind): bool => 'interface' === $kind && str_ends_with($name, 'ReporterInterface'),
    'Registry' => static fn (string $name, string $kind): bool => str_ends_with($name, 'Registry'),
    'RegistryInterface' => static fn (string $name, string $kind): bool => 'interface' === $kind && str_ends_with($name, 'RegistryInterface'),
    'Resolver' => static fn (string $name, string $kind): bool => str_ends_with($name, 'Resolver'),
    'ResolverInterface' => static fn (string $name, string $kind): bool => 'interface' === $kind && str_ends_with($name, 'ResolverInterface'),
    'Normalizer' => static fn (string $name, string $kind): bool => str_ends_with($name, 'Normalizer'),
    'NormalizerInterface' => static fn (string $name, string $kind): bool => 'interface' === $kind && str_ends_with($name, 'NormalizerInterface'),
    'ValueObject' => static fn (string $name, string $kind): bool => in_array($kind, ['class', 'enum'], true)
        && !str_ends_with($name, 'Service')
        && !str_ends_with($name, 'Reporter')
        && !str_ends_with($name, 'Registry')
        && !str_ends_with($name, 'Resolver')
        && !str_ends_with($name, 'Normalizer')
        && !str_ends_with($name, 'Factory')
        && !str_ends_with($name, 'Interface')
        && !str_ends_with($name, 'Contract')
        && !str_ends_with($name, 'Manifest')
        && !str_ends_with($name, 'Report')
        && !str_ends_with($name, 'Packet')
        && !str_ends_with($name, 'Decision')
        && !str_ends_with($name, 'Surface')
        && !str_ends_with($name, 'Trait'),
];

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($src, FilesystemIterator::SKIP_DOTS));
foreach ($iterator as $file) {
    if (!$file instanceof SplFileInfo || 'php' !== $file->getExtension()) {
        continue;
    }

    $relative = str_replace('\\', '/', substr($file->getPathname(), strlen($src) + 1));
    $topLayer = explode('/', $relative, 2)[0];

    if ('Service' === $topLayer || 'ServiceInterface' === $topLayer) {
        $errors[] = sprintf('Old broad service layer is forbidden for suffix-routed Objecting classes: %s', $relative);
        continue;
    }

    if (!array_key_exists($topLayer, $rules)) {
        continue;
    }

    $contents = (string) file_get_contents($file->getPathname());
    if (!preg_match('/\b(class|interface|trait|enum)\s+([A-Za-z_][A-Za-z0-9_]*)/', $contents, $matches)) {
        $errors[] = sprintf('Unable to detect PHP symbol in %s', $relative);
        continue;
    }

    $kind = $matches[1];
    $name = $matches[2];
    if (!$rules[$topLayer]($name, $kind)) {
        $errors[] = sprintf('Layer/suffix mismatch: %s contains %s %s.', $relative, $kind, $name);
    }
}

if ([] !== $errors) {
    fwrite(STDERR, "Objecting layer/suffix check failed:\n - " . implode("\n - ", $errors) . "\n");
    exit(1);
}

echo "Objecting layer/suffix check passed.\n";
