<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];

$requiredFiles = [
    'src/Contract/ObjectDoctrineMappingContract.php',
    'src/Report/ObjectDoctrineMappingReport.php',
    'src/Reporter/Doctrine/ObjectDoctrineMappingContractReporter.php',
    'src/ReporterInterface/Doctrine/ObjectDoctrineMappingContractReporterInterface.php',
    'tests/Unit/ObjectDoctrineMappingContractReporterTest.php',
    'resources/consumer/object-doctrine-mapping.example.yaml',
    'docs/integration/objecting-doctrine-mapping-contract.md',
];

foreach ($requiredFiles as $requiredFile) {
    if (!is_file($root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $requiredFile))) {
        $errors[] = 'Missing Doctrine mapping contract file: ' . $requiredFile;
    }
}

$servicesFile = $root . '/config/services.yaml';
if (is_file($servicesFile)) {
    $services = file_get_contents($servicesFile) ?: '';
    if (!str_contains($services, 'App\\Objecting\\ReporterInterface\\Doctrine\\ObjectDoctrineMappingContractReporterInterface:')) {
        $errors[] = 'config/services.yaml is missing ObjectDoctrineMappingContractReporterInterface alias.';
    }
}

$composerFile = $root . '/composer.json';
if (is_file($composerFile)) {
    $composer = json_decode((string) file_get_contents($composerFile), true);
    if (!is_array($composer)) {
        $errors[] = 'composer.json is not valid JSON.';
    } else {
        foreach (['test:doctrine-mapping', 'test:quality'] as $script) {
            if (!array_key_exists($script, $composer['scripts'] ?? [])) {
                $errors[] = 'composer.json is missing script: ' . $script;
            }
        }
        $qualityScript = $composer['scripts']['test:quality'] ?? [];
        $qualityScriptText = is_array($qualityScript) ? implode('\n', $qualityScript) : (string) $qualityScript;
        if (!str_contains($qualityScriptText, '@test:doctrine-mapping')) {
            $errors[] = 'composer test:quality must include @test:doctrine-mapping.';
        }
    }
}

$exampleFile = $root . '/resources/consumer/object-doctrine-mapping.example.yaml';
if (is_file($exampleFile)) {
    $example = file_get_contents($exampleFile) ?: '';
    foreach ([
        'object_doctrine_mapping_contract_version: 1',
        'component: Paging',
        'business_stem: Page',
        'namespace: App\\Paging',
        'class: App\\Paging\\Entity\\Page',
        'table: page',
        'backend_owns_migrations: true',
        'package: objecting/object',
        'field_pack_contract: resources/objecting/Page/object-field-packs.yaml',
        'column_prefix_false: true',
        'object_columns_prefixed: true',
        '- object_identity',
        '- object_audit',
        '- object_title',
        'App\\Objecting\\Embeddable\\ObjectTitleEmbeddable',
        'App\\Objecting\\EntityTrait\\Embeddable\\ObjectTitleEmbeddableTrait',
        '- object_uuid',
        '- object_first_title',
        'php bin/console doctrine:schema:validate --skip-sync',
        'mapping_readiness:',
        'status: ready',
        'backend_migration_ownership',
    ] as $marker) {
        if (!str_contains($example, $marker)) {
            $errors[] = 'Doctrine mapping example is missing marker: ' . $marker;
        }
    }
}

$surfaceFile = $root . '/src/Surface/ObjectPackageSurface.php';
if (is_file($surfaceFile)) {
    $surface = file_get_contents($surfaceFile) ?: '';
    foreach (['DOCTRINE_MAPPING_EXAMPLE', 'DOCTRINE_MAPPING_DOC', 'DOCTRINE_MAPPING_CHECK'] as $constant) {
        if (!str_contains($surface, 'public const ' . $constant)) {
            $errors[] = 'ObjectPackageSurface is missing Doctrine mapping constant: ' . $constant;
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
        'composer test:doctrine-mapping',
        'resources/consumer/object-doctrine-mapping.example.yaml',
        'docs/integration/objecting-doctrine-mapping-contract.md',
        'doctrine_mapping_contract_ready',
    ] as $marker) {
        if (!str_contains($releaseClosure, $marker)) {
            $errors[] = 'Release closure example is missing Doctrine mapping marker: ' . $marker;
        }
    }
}

if ($errors !== []) {
    echo "Objecting Doctrine mapping contract check failed:\n";
    foreach ($errors as $error) {
        echo ' - ' . $error . "\n";
    }
    exit(1);
}

echo "Objecting Doctrine mapping contract check passed.\n";
