<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];

$requiredFiles = [
    'src/Contract/ObjectBackendCloneCleanupContract.php',
    'src/Report/ObjectBackendCloneCleanupReport.php',
    'src/Reporter/FieldPack/ObjectBackendCloneCleanupContractReporter.php',
    'src/ReporterInterface/FieldPack/ObjectBackendCloneCleanupContractReporterInterface.php',
    'tests/Unit/ObjectBackendCloneCleanupContractReporterTest.php',
    'resources/consumer/object-backend-clone-cleanup.example.yaml',
    'docs/integration/objecting-backend-clone-cleanup-contract.md',
    'tools/test/objecting_backend_clone_cleanup_contract_check.php',
];
foreach ($requiredFiles as $requiredFile) {
    if (!is_file($root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $requiredFile))) {
        $errors[] = 'Missing Objecting backend clone-cleanup file: ' . $requiredFile;
    }
}

$composerFile = $root . '/composer.json';
if (is_file($composerFile)) {
    $composer = json_decode((string) file_get_contents($composerFile), true);
    if (!is_array($composer)) {
        $errors[] = 'composer.json is not valid JSON.';
    } else {
        foreach (['test:backend-clone-cleanup', 'test:quality'] as $script) {
            if (!array_key_exists($script, $composer['scripts'] ?? [])) {
                $errors[] = 'composer.json is missing script: ' . $script;
            }
        }
        $qualityScript = $composer['scripts']['test:quality'] ?? [];
        $qualityScriptText = is_array($qualityScript) ? implode("\n", $qualityScript) : (string) $qualityScript;
        if (!str_contains($qualityScriptText, '@test:backend-clone-cleanup')) {
            $errors[] = 'composer test:quality must include @test:backend-clone-cleanup.';
        }
    }
}

$servicesFile = $root . '/config/services.yaml';
if (is_file($servicesFile)) {
    $services = file_get_contents($servicesFile) ?: '';
    if (!str_contains($services, 'App\\Objecting\\ReporterInterface\\FieldPack\\ObjectBackendCloneCleanupContractReporterInterface:')) {
        $errors[] = 'config/services.yaml is missing ObjectBackendCloneCleanupContractReporterInterface alias.';
    }
}

$surfaceFile = $root . '/src/Surface/ObjectPackageSurface.php';
if (is_file($surfaceFile)) {
    $surface = file_get_contents($surfaceFile) ?: '';
    foreach (['BACKEND_CLONE_CLEANUP_EXAMPLE', 'BACKEND_CLONE_CLEANUP_DOC', 'BACKEND_CLONE_CLEANUP_CHECK'] as $constant) {
        if (!str_contains($surface, 'public const ' . $constant)) {
            $errors[] = 'ObjectPackageSurface is missing backend clone-cleanup constant: ' . $constant;
        }
    }
}

$exampleFile = $root . '/resources/consumer/object-backend-clone-cleanup.example.yaml';
if (is_file($exampleFile)) {
    $example = file_get_contents($exampleFile) ?: '';
    foreach ([
        'object_backend_clone_cleanup_version: 1',
        'source_audit: workspace-objecting-field-pack-audit.md',
        'name: objecting/object',
        'touched_files_only: true',
        'cumulative_for_backup_only: true',
        'destructive_repository_cleanup_forbidden: true',
        'component: Addressing',
        'component: Taxating',
        'src/EntityTrait/ObjectTrait.php',
        'src/EntityTrait/ObjectAuditTrait.php',
        'src/EntityTrait/Taxation/ObjectAuditTrait.php',
        'src/EntityInterface/Common/ObjectTitleInterface.php',
        'src/EntityTrait/Taxation/TaxationObjectEntityTrait.php',
        'object_identity',
        'object_audit',
        'object_title',
        'object_state',
        'object_scope',
        'composer test:quality',
        'backend_owns_runtime: true',
        'objecting_owns_system_fields: true',
        'objecting_does_not_own_backend_migrations: true',
    ] as $marker) {
        if (!str_contains($example, $marker)) {
            $errors[] = 'Backend clone-cleanup example is missing marker: ' . $marker;
        }
    }
}

$releaseClosureFile = $root . '/resources/release/objecting-release-closure.example.yaml';
if (is_file($releaseClosureFile)) {
    $releaseClosure = file_get_contents($releaseClosureFile) ?: '';
    foreach (['objecting_wave23_backend_clone_cleanup_contract', 'resources/consumer/object-backend-clone-cleanup.example.yaml', 'docs/integration/objecting-backend-clone-cleanup-contract.md', 'backend_clone_cleanup_contract_ready'] as $marker) {
        if (!str_contains($releaseClosure, $marker)) {
            $errors[] = 'Release closure example is missing backend clone-cleanup marker: ' . $marker;
        }
    }
}

if ($errors !== []) {
    echo "Objecting backend clone-cleanup contract check failed:\n";
    foreach ($errors as $error) {
        echo ' - ' . $error . "\n";
    }
    exit(1);
}

echo "Objecting backend clone-cleanup contract check passed.\n";

