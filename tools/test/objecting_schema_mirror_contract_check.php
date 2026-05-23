<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];

$requiredFiles = [
    'src/ValueObject/ObjectSchemaMirrorContract.php',
    'src/ValueObject/ObjectSchemaMirrorReport.php',
    'src/Service/Schema/ObjectSchemaMirrorContractReporter.php',
    'src/ServiceInterface/Schema/ObjectSchemaMirrorContractReporterInterface.php',
    'tests/Unit/ObjectSchemaMirrorContractReporterTest.php',
    'resources/consumer/object-schema-mirror.example.yaml',
    'docs/integration/objecting-schema-mirror-contract.md',
];

foreach ($requiredFiles as $requiredFile) {
    if (!is_file($root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $requiredFile))) {
        $errors[] = 'Missing schema mirror contract file: ' . $requiredFile;
    }
}

$servicesFile = $root . '/config/services.yaml';
if (is_file($servicesFile)) {
    $services = file_get_contents($servicesFile) ?: '';
    if (!str_contains($services, 'App\\Objecting\\ServiceInterface\\Schema\\ObjectSchemaMirrorContractReporterInterface:')) {
        $errors[] = 'config/services.yaml is missing ObjectSchemaMirrorContractReporterInterface alias.';
    }
}

$composerFile = $root . '/composer.json';
if (is_file($composerFile)) {
    $composer = json_decode((string) file_get_contents($composerFile), true);
    if (!is_array($composer)) {
        $errors[] = 'composer.json is not valid JSON.';
    } else {
        foreach (['test:schema-mirror', 'test:quality'] as $script) {
            if (!array_key_exists($script, $composer['scripts'] ?? [])) {
                $errors[] = 'composer.json is missing script: ' . $script;
            }
        }
        $qualityScript = $composer['scripts']['test:quality'] ?? [];
        $qualityScriptText = is_array($qualityScript) ? implode("\n", $qualityScript) : (string) $qualityScript;
        if (!str_contains($qualityScriptText, '@test:schema-mirror')) {
            $errors[] = 'composer test:quality must include @test:schema-mirror.';
        }
    }
}

$exampleFile = $root . '/resources/consumer/object-schema-mirror.example.yaml';
if (is_file($exampleFile)) {
    $example = file_get_contents($exampleFile) ?: '';
    foreach ([
        'object_schema_mirror_contract_version: 1',
        'component: Paging',
        'business_stem: Page',
        'namespace: App\\Paging',
        'class: App\\Paging\\Entity\\Page',
        'table: page',
        'backend_owns_migrations: true',
        'package: smart-responsor/objecting',
        'field_pack_contract: resources/objecting/Page/object-field-packs.yaml',
        'doctrine_mapping_contract: resources/objecting/Page/object-doctrine-mapping.yaml',
        'owns_system_columns: true',
        'path: resources/schema/Page/object-schema-mirror.yaml',
        'path: contract/component/Paging/Page/page.db-schema.yaml',
        'owns_api_schema_mirror: true',
        '- object_identity',
        '- object_audit',
        '- object_title',
        '- object_uuid',
        '- object_first_title',
        '- page_status',
        'composer test:schema-mirror',
        'schema_mirror_readiness:',
        'status: ready',
        'schema_mirror_exposing_ownership',
        'schema_mirror_objecting_system_column_ownership',
        'schema_mirror_informational_only',
    ] as $marker) {
        if (!str_contains($example, $marker)) {
            $errors[] = 'Schema mirror example is missing marker: ' . $marker;
        }
    }
}

$surfaceFile = $root . '/src/ValueObject/ObjectPackageSurface.php';
if (is_file($surfaceFile)) {
    $surface = file_get_contents($surfaceFile) ?: '';
    foreach (['SCHEMA_MIRROR_EXAMPLE', 'SCHEMA_MIRROR_DOC', 'SCHEMA_MIRROR_CHECK'] as $constant) {
        if (!str_contains($surface, 'public const ' . $constant)) {
            $errors[] = 'ObjectPackageSurface is missing schema mirror constant: ' . $constant;
        }
    }
}

$releaseClosureFile = $root . '/resources/release/objecting-release-closure.example.yaml';
if (is_file($releaseClosureFile)) {
    $releaseClosure = file_get_contents($releaseClosureFile) ?: '';
    foreach ([
        'objecting_wave18_rc_stabilization',
        'objecting_wave18_rc_stabilization_cumulative.zip',
        'objecting_wave18_rc_stabilization_touched.zip',
        'composer test:schema-mirror',
        'resources/consumer/object-schema-mirror.example.yaml',
        'docs/integration/objecting-schema-mirror-contract.md',
        'schema_mirror_contract_ready',
    ] as $marker) {
        if (!str_contains($releaseClosure, $marker)) {
            $errors[] = 'Release closure example is missing schema mirror marker: ' . $marker;
        }
    }
}

if ($errors !== []) {
    echo "Objecting schema mirror contract check failed:\n";
    foreach ($errors as $error) {
        echo ' - ' . $error . "\n";
    }
    exit(1);
}

echo "Objecting schema mirror contract check passed.\n";
