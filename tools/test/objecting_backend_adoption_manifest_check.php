<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];

$requiredFiles = [
    'src/Manifest/ObjectBackendAdoptionManifest.php',
    'src/Report/ObjectBackendAdoptionReport.php',
    'src/Reporter/FieldPack/ObjectBackendAdoptionManifestReporter.php',
    'src/ReporterInterface/FieldPack/ObjectBackendAdoptionManifestReporterInterface.php',
    'tests/Unit/ObjectBackendAdoptionManifestReporterTest.php',
    'resources/consumer/object-backend-adoption.example.yaml',
    'docs/integration/objecting-backend-adoption-manifest.md',
];

foreach ($requiredFiles as $requiredFile) {
    if (!is_file($root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $requiredFile))) {
        $errors[] = 'Missing backend adoption manifest file: ' . $requiredFile;
    }
}

$servicesFile = $root . '/config/services.yaml';
if (is_file($servicesFile)) {
    $services = file_get_contents($servicesFile) ?: '';
    if (!str_contains($services, 'App\\Objecting\\ReporterInterface\\FieldPack\\ObjectBackendAdoptionManifestReporterInterface:')) {
        $errors[] = 'config/services.yaml is missing ObjectBackendAdoptionManifestReporterInterface alias.';
    }
}

$composerFile = $root . '/composer.json';
if (is_file($composerFile)) {
    $composer = json_decode((string) file_get_contents($composerFile), true);
    if (!is_array($composer)) {
        $errors[] = 'composer.json is not valid JSON.';
    } else {
        foreach (['test:backend-adoption', 'test:quality'] as $script) {
            if (!array_key_exists($script, $composer['scripts'] ?? [])) {
                $errors[] = 'composer.json is missing script: ' . $script;
            }
        }
        $qualityScript = $composer['scripts']['test:quality'] ?? [];
        $qualityScriptText = is_array($qualityScript) ? implode('\n', $qualityScript) : (string) $qualityScript;
        if (!str_contains($qualityScriptText, '@test:backend-adoption')) {
            $errors[] = 'composer test:quality must include @test:backend-adoption.';
        }
    }
}

$exampleFile = $root . '/resources/consumer/object-backend-adoption.example.yaml';
if (is_file($exampleFile)) {
    $example = file_get_contents($exampleFile) ?: '';
    foreach ([
        'object_backend_adoption_version: 1',
        'component: Paging',
        'business_stem: Page',
        'namespace: App\\Paging',
        'entity: App\\Paging\\Entity\\Page',
        'table: page',
        'standalone_ready: true',
        'field_pack_profile: object_content',
        'effective_field_packs:',
        'title_alias_profile: object_title_content',
        'exposing_contract:',
        'backend_ownership:',
        'objecting_ownership:',
        'adoption_readiness:',
        'status: ready',
    ] as $marker) {
        if (!str_contains($example, $marker)) {
            $errors[] = 'Backend adoption example is missing marker: ' . $marker;
        }
    }

    foreach (['object_identity', 'object_audit', 'object_title'] as $baselinePack) {
        if (!str_contains($example, '- ' . $baselinePack)) {
            $errors[] = 'Backend adoption example is missing baseline field pack: ' . $baselinePack;
        }
    }
}

$surfaceFile = $root . '/src/Surface/ObjectPackageSurface.php';
if (is_file($surfaceFile)) {
    $surface = file_get_contents($surfaceFile) ?: '';
    foreach (['BACKEND_ADOPTION_EXAMPLE', 'BACKEND_ADOPTION_DOC', 'BACKEND_ADOPTION_CHECK'] as $constant) {
        if (!str_contains($surface, 'public const ' . $constant)) {
            $errors[] = 'ObjectPackageSurface is missing backend adoption constant: ' . $constant;
        }
    }
}

if ($errors !== []) {
    echo "Objecting backend adoption manifest check failed:\n";
    foreach ($errors as $error) {
        echo ' - ' . $error . "\n";
    }
    exit(1);
}

echo "Objecting backend adoption manifest check passed.\n";
