<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];

$requiredFiles = [
    'src/Manifest/ObjectSiblingPilotMigrationHandoffManifest.php',
    'src/Report/ObjectSiblingPilotMigrationHandoffReport.php',
    'src/Reporter/FieldPack/ObjectSiblingPilotMigrationHandoffReporter.php',
    'src/ReporterInterface/FieldPack/ObjectSiblingPilotMigrationHandoffReporterInterface.php',
    'tests/Unit/ObjectSiblingPilotMigrationHandoffReporterTest.php',
    'resources/consumer/object-sibling-pilot-migration-handoff.example.yaml',
    'docs/integration/objecting-sibling-pilot-migration-handoff.md',
    'tools/test/objecting_sibling_pilot_migration_handoff_check.php',
];
foreach ($requiredFiles as $requiredFile) {
    if (!is_file($root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $requiredFile))) {
        $errors[] = 'Missing Objecting sibling pilot migration handoff file: ' . $requiredFile;
    }
}

$composerFile = $root . '/composer.json';
if (is_file($composerFile)) {
    $composer = json_decode((string) file_get_contents($composerFile), true);
    if (!is_array($composer)) {
        $errors[] = 'composer.json is not valid JSON.';
    } else {
        foreach (['test:sibling-pilot-migration-handoff', 'test:quality'] as $script) {
            if (!array_key_exists($script, $composer['scripts'] ?? [])) {
                $errors[] = 'composer.json is missing script: ' . $script;
            }
        }
        $qualityScript = $composer['scripts']['test:quality'] ?? [];
        $qualityScriptText = is_array($qualityScript) ? implode("\n", $qualityScript) : (string) $qualityScript;
        if (!str_contains($qualityScriptText, '@test:sibling-pilot-migration-handoff')) {
            $errors[] = 'composer test:quality must include @test:sibling-pilot-migration-handoff.';
        }
    }
}

$servicesFile = $root . '/config/services.yaml';
if (is_file($servicesFile)) {
    $services = file_get_contents($servicesFile) ?: '';
    if (!str_contains($services, 'App\\Objecting\\ReporterInterface\\FieldPack\\ObjectSiblingPilotMigrationHandoffReporterInterface:')) {
        $errors[] = 'config/services.yaml is missing ObjectSiblingPilotMigrationHandoffReporterInterface alias.';
    }
}

$surfaceFile = $root . '/src/Surface/ObjectPackageSurface.php';
if (is_file($surfaceFile)) {
    $surface = file_get_contents($surfaceFile) ?: '';
    foreach (['SIBLING_PILOT_MIGRATION_HANDOFF_EXAMPLE', 'SIBLING_PILOT_MIGRATION_HANDOFF_DOC', 'SIBLING_PILOT_MIGRATION_HANDOFF_CHECK'] as $constant) {
        if (!str_contains($surface, 'public const ' . $constant)) {
            $errors[] = 'ObjectPackageSurface is missing sibling pilot migration handoff constant: ' . $constant;
        }
    }
}

$exampleFile = $root . '/resources/consumer/object-sibling-pilot-migration-handoff.example.yaml';
if (is_file($exampleFile)) {
    $example = file_get_contents($exampleFile) ?: '';
    foreach ([
        'object_sibling_pilot_migration_handoff_version: 1',
        'objecting_baseline: objecting_rc2',
        'name: objecting/object',
        'php: ^8.4',
        'symfony: ^8.0',
        'objecting_locked: true',
        'exposing_locked: true',
        'sibling_components_can_be_modified: true',
        'touched_files_only: true',
        'cumulative_for_backup_only: true',
        'destructive_repository_cleanup_forbidden: true',
        '- Addressing',
        '- Taxating',
        'object_identity',
        'object_audit',
        'object_title',
        'object_state',
        'object_source',
        'object_fingerprint',
        'object_scope',
        'nameEntity',
        'title',
        'description',
        'shortDescription',
        'label',
        'displayName',
        'priority',
        'visibility',
        'object_id',
        'object_name',
        'object_description',
        'object_priority',
        'object_visibility',
        'src/EntityTrait/ObjectTrait.php',
        'src/EntityTrait/ObjectAuditTrait.php',
        'src/EntityTrait/Taxation/ObjectAuditTrait.php',
        'src/EntityInterface/Common/ObjectTitleInterface.php',
        'src/EntityTrait/Taxation/TaxationObjectEntityTrait.php',
        'no full repository overwrite',
        'no destructive repository cleanup',
        'no /src/Domain/',
        'no Port and Adapter pattern',
        'no Symfony 7 constraints',
        'status: ready',
    ] as $marker) {
        if (!str_contains($example, $marker)) {
            $errors[] = 'Sibling pilot migration handoff example is missing marker: ' . $marker;
        }
    }
}

$releaseClosureFile = $root . '/resources/release/objecting-release-closure.example.yaml';
if (is_file($releaseClosureFile)) {
    $releaseClosure = file_get_contents($releaseClosureFile) ?: '';
    foreach (['objecting_wave26_sibling_pilot_migration_handoff', 'resources/consumer/object-sibling-pilot-migration-handoff.example.yaml', 'docs/integration/objecting-sibling-pilot-migration-handoff.md', 'sibling_pilot_migration_handoff_ready'] as $marker) {
        if (!str_contains($releaseClosure, $marker)) {
            $errors[] = 'Release closure example is missing sibling pilot migration handoff marker: ' . $marker;
        }
    }
}

if ($errors !== []) {
    echo "Objecting sibling pilot migration handoff check failed:\n";
    foreach ($errors as $error) {
        echo ' - ' . $error . "\n";
    }
    exit(1);
}

echo "Objecting sibling pilot migration handoff check passed.\n";

