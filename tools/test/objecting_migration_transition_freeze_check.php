<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];

$requiredFiles = [
    'src/Manifest/ObjectMigrationTransitionFreezeManifest.php',
    'src/Report/ObjectMigrationTransitionFreezeReport.php',
    'src/Reporter/Release/ObjectMigrationTransitionFreezeManifestReporter.php',
    'src/ReporterInterface/Release/ObjectMigrationTransitionFreezeManifestReporterInterface.php',
    'tests/Unit/ObjectMigrationTransitionFreezeManifestReporterTest.php',
    'resources/release/objecting-migration-transition-freeze.example.yaml',
    'docs/release/objecting-migration-transition-freeze.md',
    'tools/test/objecting_migration_transition_freeze_check.php',
];
foreach ($requiredFiles as $requiredFile) {
    if (!is_file($root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $requiredFile))) {
        $errors[] = 'Missing Objecting migration transition freeze file: ' . $requiredFile;
    }
}

$composerFile = $root . '/composer.json';
if (is_file($composerFile)) {
    $composer = json_decode((string) file_get_contents($composerFile), true);
    if (!is_array($composer)) {
        $errors[] = 'composer.json is not valid JSON.';
    } else {
        foreach (['test:migration-transition-freeze', 'test:quality'] as $script) {
            if (!array_key_exists($script, $composer['scripts'] ?? [])) {
                $errors[] = 'composer.json is missing script: ' . $script;
            }
        }
        $qualityScript = $composer['scripts']['test:quality'] ?? [];
        $qualityScriptText = is_array($qualityScript) ? implode("
", $qualityScript) : (string) $qualityScript;
        if (!str_contains($qualityScriptText, '@test:migration-transition-freeze')) {
            $errors[] = 'composer test:quality must include @test:migration-transition-freeze.';
        }
    }
}

$servicesFile = $root . '/config/services.yaml';
if (is_file($servicesFile)) {
    $services = file_get_contents($servicesFile) ?: '';
    if (!str_contains($services, 'App\Objecting\ReporterInterface\Release\ObjectMigrationTransitionFreezeManifestReporterInterface:')) {
        $errors[] = 'config/services.yaml is missing ObjectMigrationTransitionFreezeManifestReporterInterface alias.';
    }
}

$surfaceFile = $root . '/src/Surface/ObjectPackageSurface.php';
if (is_file($surfaceFile)) {
    $surface = file_get_contents($surfaceFile) ?: '';
    foreach (['MIGRATION_TRANSITION_FREEZE_EXAMPLE', 'MIGRATION_TRANSITION_FREEZE_DOC', 'MIGRATION_TRANSITION_FREEZE_CHECK'] as $constant) {
        if (!str_contains($surface, 'public const ' . $constant)) {
            $errors[] = 'ObjectPackageSurface is missing migration transition freeze constant: ' . $constant;
        }
    }
}

$exampleFile = $root . '/resources/release/objecting-migration-transition-freeze.example.yaml';
if (is_file($exampleFile)) {
    $example = file_get_contents($exampleFile) ?: '';
    foreach ([
        'object_migration_transition_freeze_version: 1',
        'name: objecting_migration_transition_freeze',
        'objecting_baseline: objecting_rc2',
        'closure_candidate: objecting_wave27_migration_transition_freeze',
        'cumulative_archive: objecting_wave27_migration_transition_freeze_cumulative.zip',
        'touched_archive: objecting_wave27_migration_transition_freeze_touched.zip',
        'next_track: backend_component_migration',
        'objecting_frozen: true',
        'exposing_frozen: true',
        'backend_migration_open: true',
        'touched_files_only: true',
        'cumulative_for_backup_only: true',
        'destructive_repository_cleanup_forbidden: true',
        '- Addressing',
        '- Taxating',
        'resources/release/objecting-rc2.example.yaml',
        'resources/consumer/object-sibling-pilot-migration-handoff.example.yaml',
        'resources/consumer/object-backend-migration-command.example.yaml',
        'resources/consumer/object-backend-clone-cleanup.example.yaml',
        'tools/test/objecting_systemic_field_pack_check.php',
        'tools/test/objecting_title_alias_hardening_check.php',
        'composer test:quality',
        'composer test:sibling-pilot-migration-handoff',
        'composer test:backend-migration-command',
        'no Objecting expansion during pilot migration',
        'no Exposing changes during pilot migration',
        'no full repository overwrite',
        'no destructive repository cleanup',
        'no Symfony 7 constraints',
        'status: ready',
    ] as $marker) {
        if (!str_contains($example, $marker)) {
            $errors[] = 'Migration transition freeze example is missing marker: ' . $marker;
        }
    }
}

$releaseClosureFile = $root . '/resources/release/objecting-release-closure.example.yaml';
if (is_file($releaseClosureFile)) {
    $releaseClosure = file_get_contents($releaseClosureFile) ?: '';
    foreach (['objecting_wave27_migration_transition_freeze', 'resources/release/objecting-migration-transition-freeze.example.yaml', 'docs/release/objecting-migration-transition-freeze.md', 'migration_transition_freeze_ready'] as $marker) {
        if (!str_contains($releaseClosure, $marker)) {
            $errors[] = 'Release closure example is missing migration transition freeze marker: ' . $marker;
        }
    }
}

if ($errors !== []) {
    echo "Objecting migration transition freeze check failed:
";
    foreach ($errors as $error) {
        echo ' - ' . $error . "
";
    }
    exit(1);
}

echo "Objecting migration transition freeze check passed.
";

