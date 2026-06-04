<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];

$requiredFiles = [
    'src/Reporter/FieldPack/ObjectBackendMigrationReadinessReporter.php',
    'src/ReporterInterface/FieldPack/ObjectBackendMigrationReadinessReporterInterface.php',
    'src/Report/ObjectBackendMigrationReadinessReport.php',
    'tests/Unit/ObjectBackendMigrationReadinessReporterTest.php',
    'resources/consumer/object-backend-migration-readiness.example.yaml',
    'docs/integration/objecting-backend-migration-readiness.md',
];

foreach ($requiredFiles as $requiredFile) {
    if (!is_file($root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $requiredFile))) {
        $errors[] = 'Missing backend migration readiness file: ' . $requiredFile;
    }
}

$servicesFile = $root . '/config/services.yaml';
if (is_file($servicesFile)) {
    $services = file_get_contents($servicesFile) ?: '';
    if (!str_contains($services, 'App\\Objecting\\ReporterInterface\\FieldPack\\ObjectBackendMigrationReadinessReporterInterface:')) {
        $errors[] = 'config/services.yaml is missing ObjectBackendMigrationReadinessReporterInterface alias.';
    }
}

$composerFile = $root . '/composer.json';
if (is_file($composerFile)) {
    $composer = json_decode((string) file_get_contents($composerFile), true);
    if (!is_array($composer)) {
        $errors[] = 'composer.json is not valid JSON.';
    } else {
        foreach (['test:migration-readiness', 'test:quality'] as $script) {
            if (!array_key_exists($script, $composer['scripts'] ?? [])) {
                $errors[] = 'composer.json is missing script: ' . $script;
            }
        }
        $qualityScript = $composer['scripts']['test:quality'] ?? [];
        $qualityScriptText = is_array($qualityScript) ? implode('\n', $qualityScript) : (string) $qualityScript;
        if (!str_contains($qualityScriptText, '@test:migration-readiness')) {
            $errors[] = 'composer test:quality must include @test:migration-readiness.';
        }
    }
}

$exampleFile = $root . '/resources/consumer/object-backend-migration-readiness.example.yaml';
if (is_file($exampleFile)) {
    $example = file_get_contents($exampleFile) ?: '';
    foreach (['object_identity', 'object_audit', 'object_title'] as $baselinePack) {
        if (!str_contains($example, '- ' . $baselinePack)) {
            $errors[] = 'Backend migration readiness example is missing baseline field pack: ' . $baselinePack;
        }
    }
    foreach (['migration_readiness:', 'status: ready', 'component_entity_namespace', 'business_stem_entity_suffix'] as $requiredText) {
        if (!str_contains($example, $requiredText)) {
            $errors[] = 'Backend migration readiness example is missing marker: ' . $requiredText;
        }
    }
}

$reportFile = $root . '/src/Reporter/FieldPack/ObjectBackendMigrationReadinessReporter.php';
if (is_file($reportFile)) {
    $reporter = file_get_contents($reportFile) ?: '';
    foreach (['ObjectFieldPackName::IDENTITY', 'ObjectFieldPackName::AUDIT', 'ObjectFieldPackName::TITLE'] as $baselineConstant) {
        if (!str_contains($reporter, $baselineConstant)) {
            $errors[] = 'ObjectBackendMigrationReadinessReporter baseline is missing: ' . $baselineConstant;
        }
    }
}

if ($errors !== []) {
    echo "Objecting backend migration readiness check failed:\n";
    foreach ($errors as $error) {
        echo ' - ' . $error . "\n";
    }
    exit(1);
}

echo "Objecting backend migration readiness check passed.\n";
