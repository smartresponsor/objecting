<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];
$manifest = file_get_contents($root . '/resources/field-pack/manifest.yaml');
if (!is_string($manifest)) {
    fwrite(STDERR, "Cannot read Objecting field-pack manifest.\n");
    exit(1);
}

preg_match_all('/^\s+-\s+(object_[a-z0-9_]+)$/m', $manifest, $matches);
$fieldPacks = array_values(array_unique($matches[1] ?? []));
if ([] === $fieldPacks) {
    fwrite(STDERR, "Objecting field-pack manifest declares no packs.\n");
    exit(1);
}
$requiredMarkers = [
    'semantic_version:',
    'stability:',
    'purpose:',
    'universal: true',
    'business_neutral: true',
    'schema_version:',
    'public_api_version:',
    'security_classification:',
    'ownership:',
    'mapping: Objecting',
    'migration: Consumer',
    'api_projection: Exposing',
    'invariants:',
];

foreach ($fieldPacks as $fieldPack) {
    $fileName = str_replace('_', '-', $fieldPack) . '.yaml';
    $path = $root . '/resources/field-pack/' . $fileName;
    $contents = file_get_contents($path);
    if (!is_string($contents)) {
        $errors[] = 'Cannot read field-pack manifest: ' . $fileName;
        continue;
    }

    foreach ($requiredMarkers as $marker) {
        if (!str_contains($contents, $marker)) {
            $errors[] = sprintf('%s is missing semantic marker %s', $fileName, $marker);
        }
    }

    if (!preg_match('/^\s+-\s+[a-z][a-z0-9_]+$/m', $contents)) {
        $errors[] = $fileName . ' has no machine-readable invariant identifiers.';
    }

    if (!str_contains($contents, 'name: ' . $fieldPack)) {
        $errors[] = $fileName . ' does not match manifest field-pack name ' . $fieldPack . '.';
    }

    if (1 !== preg_match('/^purpose:\s+\S.+$/m', $contents)) {
        $errors[] = $fileName . ' has an empty or invalid purpose.';
    }
}

if ([] !== $errors) {
    fwrite(STDERR, "Objecting semantic manifest check failed:\n- " . implode("\n- ", $errors) . "\n");
    exit(1);
}

echo "Objecting semantic manifest check passed.\n";
