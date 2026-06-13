<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];

$requiredFiles = [
    'src/Manifest/ObjectBackendHandoffManifest.php',
    'src/Report/ObjectBackendHandoffReport.php',
    'src/Reporter/FieldPack/ObjectBackendHandoffManifestReporter.php',
    'src/ReporterInterface/FieldPack/ObjectBackendHandoffManifestReporterInterface.php',
    'tests/Unit/ObjectBackendHandoffManifestReporterTest.php',
    'resources/consumer/object-backend-handoff.example.yaml',
    'docs/integration/objecting-backend-handoff-contract.md',
];

foreach ($requiredFiles as $requiredFile) {
    if (!is_file($root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $requiredFile))) {
        $errors[] = 'Missing backend handoff contract file: ' . $requiredFile;
    }
}

$servicesFile = $root . '/config/services.yaml';
if (is_file($servicesFile)) {
    $services = file_get_contents($servicesFile) ?: '';
    if (!str_contains($services, 'App\\Objecting\\ReporterInterface\\FieldPack\\ObjectBackendHandoffManifestReporterInterface:')) {
        $errors[] = 'config/services.yaml is missing ObjectBackendHandoffManifestReporterInterface alias.';
    }
}

$composerFile = $root . '/composer.json';
if (is_file($composerFile)) {
    $composer = json_decode((string) file_get_contents($composerFile), true);
    if (!is_array($composer)) {
        $errors[] = 'composer.json is not valid JSON.';
    } else {
        foreach (['test:backend-handoff', 'test:quality'] as $script) {
            if (!array_key_exists($script, $composer['scripts'] ?? [])) {
                $errors[] = 'composer.json is missing script: ' . $script;
            }
        }
        $qualityScript = $composer['scripts']['test:quality'] ?? [];
        $qualityScriptText = is_array($qualityScript) ? implode('\n', $qualityScript) : (string) $qualityScript;
        if (!str_contains($qualityScriptText, '@test:backend-handoff')) {
            $errors[] = 'composer test:quality must include @test:backend-handoff.';
        }
    }
}

$exampleFile = $root . '/resources/consumer/object-backend-handoff.example.yaml';
if (is_file($exampleFile)) {
    $example = file_get_contents($exampleFile) ?: '';
    foreach ([
        'object_backend_handoff_version: 1',
        'component: Paging',
        'business_stem: Page',
        'namespace: App\\Paging',
        'name: objecting/object',
        'constraint: ^1.0',
        'backend_project_root: D:\\PhpstormProjects\\www\\Paging',
        'adoption_manifest: resources/objecting/Page/object-backend-adoption.yaml',
        'readiness_manifest: resources/objecting/Page/object-backend-migration-readiness.yaml',
        'composer dump-autoload',
        'composer test:quality',
        'test:canon',
        'test:quality',
        'path: contract/component/Paging/Page/manifest.yaml',
        'standalone_ready: true',
        'handoff_readiness:',
        'status: ready',
    ] as $marker) {
        if (!str_contains($example, $marker)) {
            $errors[] = 'Backend handoff example is missing marker: ' . $marker;
        }
    }
}

$surfaceFile = $root . '/src/Surface/ObjectPackageSurface.php';
if (is_file($surfaceFile)) {
    $surface = file_get_contents($surfaceFile) ?: '';
    foreach (['BACKEND_HANDOFF_EXAMPLE', 'BACKEND_HANDOFF_DOC', 'BACKEND_HANDOFF_CHECK'] as $constant) {
        if (!str_contains($surface, 'public const ' . $constant)) {
            $errors[] = 'ObjectPackageSurface is missing backend handoff constant: ' . $constant;
        }
    }
}

if ($errors !== []) {
    echo "Objecting backend handoff contract check failed:\n";
    foreach ($errors as $error) {
        echo ' - ' . $error . "\n";
    }
    exit(1);
}

echo "Objecting backend handoff contract check passed.\n";

