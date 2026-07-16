<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];
$criticalPacks = [
    'object-audit.yaml',
    'object-lock.yaml',
    'object-soft-delete.yaml',
    'object-title.yaml',
];
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

foreach ($criticalPacks as $fileName) {
    $path = $root . '/resources/field-pack/' . $fileName;
    $contents = file_get_contents($path);
    if (!is_string($contents)) {
        $errors[] = 'Cannot read critical field-pack manifest: ' . $fileName;
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
}

if ([] !== $errors) {
    fwrite(STDERR, "Objecting semantic manifest check failed:\n- " . implode("\n- ", $errors) . "\n");
    exit(1);
}

echo "Objecting semantic manifest check passed.\n";
