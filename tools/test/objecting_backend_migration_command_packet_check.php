<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];

$requiredFiles = [
    'src/Packet/ObjectBackendMigrationCommandPacket.php',
    'src/Report/ObjectBackendMigrationCommandReport.php',
    'src/Reporter/FieldPack/ObjectBackendMigrationCommandPacketReporter.php',
    'src/ReporterInterface/FieldPack/ObjectBackendMigrationCommandPacketReporterInterface.php',
    'tests/Unit/ObjectBackendMigrationCommandPacketReporterTest.php',
    'resources/consumer/object-backend-migration-command.example.yaml',
    'docs/integration/objecting-backend-migration-command-packet.md',
    'tools/test/objecting_backend_migration_command_packet_check.php',
];
foreach ($requiredFiles as $requiredFile) {
    if (!is_file($root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $requiredFile))) {
        $errors[] = 'Missing Objecting backend migration command file: ' . $requiredFile;
    }
}

$composerFile = $root . '/composer.json';
if (is_file($composerFile)) {
    $composer = json_decode((string) file_get_contents($composerFile), true);
    if (!is_array($composer)) {
        $errors[] = 'composer.json is not valid JSON.';
    } else {
        foreach (['test:backend-migration-command', 'test:quality'] as $script) {
            if (!array_key_exists($script, $composer['scripts'] ?? [])) {
                $errors[] = 'composer.json is missing script: ' . $script;
            }
        }
        $qualityScript = $composer['scripts']['test:quality'] ?? [];
        $qualityScriptText = is_array($qualityScript) ? implode("\n", $qualityScript) : (string) $qualityScript;
        if (!str_contains($qualityScriptText, '@test:backend-migration-command')) {
            $errors[] = 'composer test:quality must include @test:backend-migration-command.';
        }
    }
}

$servicesFile = $root . '/config/services.yaml';
if (is_file($servicesFile)) {
    $services = file_get_contents($servicesFile) ?: '';
    if (!str_contains($services, 'App\\Objecting\\ReporterInterface\\FieldPack\\ObjectBackendMigrationCommandPacketReporterInterface:')) {
        $errors[] = 'config/services.yaml is missing ObjectBackendMigrationCommandPacketReporterInterface alias.';
    }
}

$surfaceFile = $root . '/src/Surface/ObjectPackageSurface.php';
if (is_file($surfaceFile)) {
    $surface = file_get_contents($surfaceFile) ?: '';
    foreach (['BACKEND_MIGRATION_COMMAND_EXAMPLE', 'BACKEND_MIGRATION_COMMAND_DOC', 'BACKEND_MIGRATION_COMMAND_CHECK'] as $constant) {
        if (!str_contains($surface, 'public const ' . $constant)) {
            $errors[] = 'ObjectPackageSurface is missing backend migration command constant: ' . $constant;
        }
    }
}

$exampleFile = $root . '/resources/consumer/object-backend-migration-command.example.yaml';
if (is_file($exampleFile)) {
    $example = file_get_contents($exampleFile) ?: '';
    foreach ([
        'object_backend_migration_command_version: 1',
        'source_audit: workspace-objecting-field-pack-audit.md',
        'name: objecting/object',
        'php: ^8.4',
        'symfony: ^8.0',
        'objecting_can_be_modified: false',
        'exposing_can_be_modified: false',
        'sibling_components_can_be_modified: true',
        'touched_files_only: true',
        'cumulative_for_backup_only: true',
        'destructive_repository_cleanup_forbidden: true',
        'pilot_components:',
        '- Addressing',
        '- Taxating',
        'object_identity',
        'object_audit',
        'object_title',
        'object_state',
        'object_source',
        'object_fingerprint',
        'object_scope',
        'id: backend-owned Doctrine primary key',
        'name: object_title alias',
        'title: object_title alias',
        'description: object_title alias',
        'priority',
        'visibility',
        'no full repository overwrite',
        'no /src/Domain/',
        'no Port and Adapter pattern',
        'no Symfony 7 constraints',
        'composer dump-autoload',
        'composer test:quality',
        'migration_command_readiness:',
        'status: ready',
        'objecting_locked',
        'exposing_locked',
    ] as $marker) {
        if (!str_contains($example, $marker)) {
            $errors[] = 'Backend migration command example is missing marker: ' . $marker;
        }
    }
}

$releaseClosureFile = $root . '/resources/release/objecting-release-closure.example.yaml';
if (is_file($releaseClosureFile)) {
    $releaseClosure = file_get_contents($releaseClosureFile) ?: '';
    foreach (['objecting_wave24_backend_migration_command_packet', 'resources/consumer/object-backend-migration-command.example.yaml', 'docs/integration/objecting-backend-migration-command-packet.md', 'backend_migration_command_packet_ready'] as $marker) {
        if (!str_contains($releaseClosure, $marker)) {
            $errors[] = 'Release closure example is missing backend migration command marker: ' . $marker;
        }
    }
}

if ($errors !== []) {
    echo "Objecting backend migration command packet check failed:\n";
    foreach ($errors as $error) {
        echo ' - ' . $error . "\n";
    }
    exit(1);
}

echo "Objecting backend migration command packet check passed.\n";

