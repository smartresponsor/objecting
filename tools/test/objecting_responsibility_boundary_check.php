<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];

foreach ([
    'OBJECTING_RC2_HARDENING_PLAN.md',
    'docs/architecture/objecting-responsibility-boundary.md',
] as $requiredFile) {
    if (!is_file($root . '/' . $requiredFile)) {
        $errors[] = 'Missing responsibility-boundary artifact: ' . $requiredFile;
    }
}

foreach ([
    'src/Controller',
    'src/Domain',
    'src/Dto',
    'src/Entity',
    'src/Form',
    'src/Http',
    'src/Migration',
    'src/Migrations',
    'src/Repository',
    'config/routes',
    'migrations',
    'public',
] as $forbiddenPath) {
    if (file_exists($root . '/' . $forbiddenPath)) {
        $errors[] = 'Forbidden Objecting ownership path exists: ' . $forbiddenPath;
    }
}

$composer = json_decode(
    (string) file_get_contents($root . '/composer.json'),
    true,
    512,
    JSON_THROW_ON_ERROR,
);
$declaredPackages = array_merge(
    array_keys($composer['require'] ?? []),
    array_keys($composer['require-dev'] ?? []),
);

foreach ([
    'api-platform/core',
    'nelmio/api-doc-bundle',
    'symfony/form',
    'symfony/routing',
    'symfony/serializer',
] as $forbiddenPackage) {
    if (in_array($forbiddenPackage, $declaredPackages, true)) {
        $errors[] = 'Forbidden responsibility-expanding Composer dependency: ' . $forbiddenPackage;
    }
}

$forbiddenPatterns = [
    '/#\[Route\b/' => 'route attribute',
    '/ApiPlatform\\\\Metadata/' => 'API Platform metadata ownership',
    '/Doctrine\\\\Migrations/' => 'Doctrine migration ownership',
    '/Nelmio\\\\ApiDocBundle/' => 'Nelmio API ownership',
    '/OpenApi\\\\Attributes/' => 'OpenAPI attribute ownership',
    '/App\\\\Cruding\\\\.*Controller/' => 'Cruding controller ownership',
    '/\b(?:prePersist|preUpdate|preRemove|postLoad)\b/' => 'hidden Doctrine lifecycle listener behavior',
];

foreach (['src', 'config', 'resources'] as $scanRoot) {
    $absoluteRoot = $root . '/' . $scanRoot;
    if (!is_dir($absoluteRoot)) {
        continue;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($absoluteRoot, FilesystemIterator::SKIP_DOTS),
    );

    foreach ($iterator as $file) {
        if (!$file instanceof SplFileInfo || !$file->isFile()) {
            continue;
        }

        if (!in_array($file->getExtension(), ['php', 'xml', 'yaml', 'yml'], true)) {
            continue;
        }

        $contents = file_get_contents($file->getPathname());
        if (!is_string($contents)) {
            $errors[] = 'Cannot read boundary-scanned file: ' . $file->getPathname();
            continue;
        }

        $relativePath = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));
        foreach ($forbiddenPatterns as $pattern => $label) {
            if (1 === preg_match($pattern, $contents)) {
                $errors[] = sprintf('%s contains forbidden %s.', $relativePath, $label);
            }
        }
    }
}

if ([] !== $errors) {
    fwrite(
        STDERR,
        "Objecting responsibility boundary check failed:\n- "
        . implode("\n- ", array_values(array_unique($errors)))
        . "\n",
    );
    exit(1);
}

