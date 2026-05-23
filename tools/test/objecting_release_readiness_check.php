<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];

$requiredFiles = [
    'src/ValueObject/ObjectReleaseManifest.php',
    'src/ValueObject/ObjectReleaseReport.php',
    'src/Service/Release/ObjectReleaseManifestReporter.php',
    'src/ServiceInterface/Release/ObjectReleaseManifestReporterInterface.php',
    'tests/Unit/ObjectReleaseManifestReporterTest.php',
    'resources/release/objecting-release-manifest.example.yaml',
    'docs/integration/objecting-release-readiness.md',
];

foreach ($requiredFiles as $requiredFile) {
    if (!is_file($root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $requiredFile))) {
        $errors[] = 'Missing Objecting release-readiness file: ' . $requiredFile;
    }
}

$servicesFile = $root . '/config/services.yaml';
if (is_file($servicesFile)) {
    $services = file_get_contents($servicesFile) ?: '';
    if (!str_contains($services, 'App\\Objecting\\ServiceInterface\\Release\\ObjectReleaseManifestReporterInterface:')) {
        $errors[] = 'config/services.yaml is missing ObjectReleaseManifestReporterInterface alias.';
    }
}

$composerFile = $root . '/composer.json';
if (is_file($composerFile)) {
    $composer = json_decode((string) file_get_contents($composerFile), true);
    if (!is_array($composer)) {
        $errors[] = 'composer.json is not valid JSON.';
    } else {
        foreach (['test:release-readiness', 'test:quality'] as $script) {
            if (!array_key_exists($script, $composer['scripts'] ?? [])) {
                $errors[] = 'composer.json is missing script: ' . $script;
            }
        }
        $qualityScript = $composer['scripts']['test:quality'] ?? [];
        $qualityScriptText = is_array($qualityScript) ? implode('\n', $qualityScript) : (string) $qualityScript;
        if (!str_contains($qualityScriptText, '@test:release-readiness')) {
            $errors[] = 'composer test:quality must include @test:release-readiness.';
        }
    }
}

$manifestFile = $root . '/resources/release/objecting-release-manifest.example.yaml';
if (is_file($manifestFile)) {
    $manifest = file_get_contents($manifestFile) ?: '';
    foreach ([
        'object_release_manifest_version: 1',
        'release_candidate: objecting_wave10_release_readiness',
        'name: smart-responsor/objecting',
        'namespace_prefix: App\\Objecting\\',
        'bundle_class: App\\Objecting\\ObjectBundle',
        'cumulative_archive: objecting_wave10_release_readiness_cumulative.zip',
        'touched_archive: objecting_wave10_release_readiness_touched.zip',
        'composer dump-autoload',
        'composer test:quality',
        'test:canon',
        'test:package-surface',
        'test:release-readiness',
        'resources/consumer/object-field-packs.example.yaml',
        'resources/consumer/object-backend-migration-readiness.example.yaml',
        'resources/consumer/object-backend-adoption.example.yaml',
        'resources/consumer/object-backend-handoff.example.yaml',
        'field_pack_foundation_only: true',
        'legacy_free: true',
        'release_readiness:',
        'status: ready',
    ] as $marker) {
        if (!str_contains($manifest, $marker)) {
            $errors[] = 'Objecting release manifest example is missing marker: ' . $marker;
        }
    }
}

$surfaceFile = $root . '/src/ValueObject/ObjectPackageSurface.php';
if (is_file($surfaceFile)) {
    $surface = file_get_contents($surfaceFile) ?: '';
    foreach (['RELEASE_MANIFEST_EXAMPLE', 'RELEASE_READINESS_DOC', 'RELEASE_READINESS_CHECK'] as $constant) {
        if (!str_contains($surface, 'public const ' . $constant)) {
            $errors[] = 'ObjectPackageSurface is missing release-readiness constant: ' . $constant;
        }
    }
}

if ($errors !== []) {
    echo "Objecting release readiness check failed:\n";
    foreach ($errors as $error) {
        echo ' - ' . $error . "\n";
    }
    exit(1);
}

echo "Objecting release readiness check passed.\n";
