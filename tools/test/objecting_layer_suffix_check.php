<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$src = $root . DIRECTORY_SEPARATOR . 'src';
$errors = [];

$rules = [
    'Embeddable' => static fn (string $nameEntity, string $kind): bool => str_ends_with($nameEntity, 'Embeddable'),
    'EntityInterface' => static fn (string $nameEntity, string $kind): bool => 'interface' === $kind && str_ends_with($nameEntity, 'Interface'),
    'EntityTrait' => static fn (string $nameEntity, string $kind): bool => 'trait' === $kind && str_ends_with($nameEntity, 'Trait'),
    'Factory' => static fn (string $nameEntity, string $kind): bool => str_ends_with($nameEntity, 'Factory'),
    'Contract' => static fn (string $nameEntity, string $kind): bool => str_ends_with($nameEntity, 'Contract'),
    'Manifest' => static fn (string $nameEntity, string $kind): bool => str_ends_with($nameEntity, 'Manifest'),
    'Report' => static fn (string $nameEntity, string $kind): bool => str_ends_with($nameEntity, 'Report'),
    'Packet' => static fn (string $nameEntity, string $kind): bool => str_ends_with($nameEntity, 'Packet'),
    'Decision' => static fn (string $nameEntity, string $kind): bool => str_ends_with($nameEntity, 'Decision'),
    'Surface' => static fn (string $nameEntity, string $kind): bool => str_ends_with($nameEntity, 'Surface'),
    'Reporter' => static fn (string $nameEntity, string $kind): bool => str_ends_with($nameEntity, 'Reporter'),
    'ReporterInterface' => static fn (string $nameEntity, string $kind): bool => 'interface' === $kind && str_ends_with($nameEntity, 'ReporterInterface'),
    'Registry' => static fn (string $nameEntity, string $kind): bool => str_ends_with($nameEntity, 'Registry'),
    'RegistryInterface' => static fn (string $nameEntity, string $kind): bool => 'interface' === $kind && str_ends_with($nameEntity, 'RegistryInterface'),
    'Resolver' => static fn (string $nameEntity, string $kind): bool => str_ends_with($nameEntity, 'Resolver'),
    'ResolverInterface' => static fn (string $nameEntity, string $kind): bool => 'interface' === $kind && str_ends_with($nameEntity, 'ResolverInterface'),
    'Normalizer' => static fn (string $nameEntity, string $kind): bool => str_ends_with($nameEntity, 'Normalizer'),
    'NormalizerInterface' => static fn (string $nameEntity, string $kind): bool => 'interface' === $kind && str_ends_with($nameEntity, 'NormalizerInterface'),
    'ValueObject' => static fn (string $nameEntity, string $kind): bool => in_array($kind, ['class', 'enum'], true)
        && !str_ends_with($nameEntity, 'Service')
        && !str_ends_with($nameEntity, 'Reporter')
        && !str_ends_with($nameEntity, 'Registry')
        && !str_ends_with($nameEntity, 'Resolver')
        && !str_ends_with($nameEntity, 'Normalizer')
        && !str_ends_with($nameEntity, 'Factory')
        && !str_ends_with($nameEntity, 'Interface')
        && !str_ends_with($nameEntity, 'Contract')
        && !str_ends_with($nameEntity, 'Manifest')
        && !str_ends_with($nameEntity, 'Report')
        && !str_ends_with($nameEntity, 'Packet')
        && !str_ends_with($nameEntity, 'Decision')
        && !str_ends_with($nameEntity, 'Surface')
        && !str_ends_with($nameEntity, 'Trait'),
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
    $nameEntity = $matches[2];
    if (!$rules[$topLayer]($nameEntity, $kind)) {
        $errors[] = sprintf('Layer/suffix mismatch: %s contains %s %s.', $relative, $kind, $nameEntity);
    }
}

if ([] !== $errors) {
    fwrite(STDERR, "Objecting layer/suffix check failed:\n - " . implode("\n - ", $errors) . "\n");
    exit(1);
}

echo "Objecting layer/suffix check passed.\n";
