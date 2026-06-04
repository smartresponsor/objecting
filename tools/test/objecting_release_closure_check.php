<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];

$requiredFiles = [
    'src/Manifest/ObjectReleaseClosureManifest.php',
    'src/Report/ObjectReleaseClosureReport.php',
    'src/Reporter/Release/ObjectReleaseClosureManifestReporter.php',
    'src/ReporterInterface/Release/ObjectReleaseClosureManifestReporterInterface.php',
    'tests/Unit/ObjectReleaseClosureManifestReporterTest.php',
    'resources/release/objecting-release-closure.example.yaml',
    'docs/integration/objecting-release-closure.md',
    'docs/integration/objecting-embeddable-initialization.md',
    'docs/integration/objecting-doctrine-mapping-contract.md',
    'docs/integration/objecting-schema-mirror-contract.md',
    'docs/integration/objecting-exposing-bridge-contract.md',
    'resources/release/objecting-rc-stabilization.example.yaml',
    'docs/integration/objecting-rc-stabilization.md',
    'tools/test/objecting_rc_stabilization_check.php',
];

foreach ($requiredFiles as $requiredFile) {
    if (!is_file($root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $requiredFile))) {
        $errors[] = 'Missing Objecting release-closure file: ' . $requiredFile;
    }
}

$servicesFile = $root . '/config/services.yaml';
if (is_file($servicesFile)) {
    $services = file_get_contents($servicesFile) ?: '';
    if (!str_contains($services, 'App\\Objecting\\ReporterInterface\\Release\\ObjectReleaseClosureManifestReporterInterface:')) {
        $errors[] = 'config/services.yaml is missing ObjectReleaseClosureManifestReporterInterface alias.';
    }
}

$composerFile = $root . '/composer.json';
if (is_file($composerFile)) {
    $composer = json_decode((string) file_get_contents($composerFile), true);
    if (!is_array($composer)) {
        $errors[] = 'composer.json is not valid JSON.';
    } else {
        foreach (['test:release-closure', 'test:rc-stabilization', 'test:embeddable-initialization', 'test:doctrine-mapping', 'test:schema-mirror', 'test:exposing-bridge', 'test:quality'] as $script) {
            if (!array_key_exists($script, $composer['scripts'] ?? [])) {
                $errors[] = 'composer.json is missing script: ' . $script;
            }
        }
        $qualityScript = $composer['scripts']['test:quality'] ?? [];
        $qualityScriptText = is_array($qualityScript) ? implode("\n", $qualityScript) : (string) $qualityScript;
        if (!str_contains($qualityScriptText, '@test:rc-stabilization')) {
            $errors[] = 'composer test:quality must include @test:rc-stabilization.';
        }
        if (!str_contains($qualityScriptText, '@test:release-closure')) {
            $errors[] = 'composer test:quality must include @test:release-closure.';
        }
        if (!str_contains($qualityScriptText, '@test:embeddable-initialization')) {
            $errors[] = 'composer test:quality must include @test:embeddable-initialization.';
        }
        if (!str_contains($qualityScriptText, '@test:doctrine-mapping')) {
            $errors[] = 'composer test:quality must include @test:doctrine-mapping.';
        }
        if (!str_contains($qualityScriptText, '@test:schema-mirror')) {
            $errors[] = 'composer test:quality must include @test:schema-mirror.';
        }
        if (!str_contains($qualityScriptText, '@test:exposing-bridge')) {
            $errors[] = 'composer test:quality must include @test:exposing-bridge.';
        }
    }
}

$closureFile = $root . '/resources/release/objecting-release-closure.example.yaml';
if (is_file($closureFile)) {
    $closure = file_get_contents($closureFile) ?: '';
    foreach ([
        'object_release_closure_version: 1',
        'closure_candidate: objecting_wave18_rc_stabilization',
        'name: objecting/object',
        'namespace_prefix: App\\Objecting\\',
        'bundle_class: App\\Objecting\\ObjectBundle',
        'cumulative_archive: objecting_wave18_rc_stabilization_cumulative.zip',
        'touched_archive: objecting_wave18_rc_stabilization_touched.zip',
        'composer dump-autoload',
        'composer test:quality',
        'composer test:rc-stabilization',
        'composer test:embeddable-initialization',
        'composer test:doctrine-mapping',
        'composer test:schema-mirror',
        'composer test:exposing-bridge',
        'resources/release/objecting-release-manifest.example.yaml',
        'resources/release/objecting-release-closure.example.yaml',
        'resources/release/objecting-rc-stabilization.example.yaml',
        'docs/integration/objecting-rc-stabilization.md',
        'docs/integration/objecting-embeddable-initialization.md',
        'docs/integration/objecting-doctrine-mapping-contract.md',
        'docs/integration/objecting-schema-mirror-contract.md',
        'docs/integration/objecting-exposing-bridge-contract.md',
    'docs/integration/objecting-exposing-bridge-contract.md',
        'resources/field-pack/manifest.yaml',
        'resources/title-alias/manifest.yaml',
        'resources/consumer/object-field-packs.example.yaml',
        'resources/consumer/object-backend-migration-readiness.example.yaml',
        'resources/consumer/object-backend-adoption.example.yaml',
        'resources/consumer/object-backend-handoff.example.yaml',
        'resources/consumer/object-backend-adoption-packet.example.yaml',
        'resources/consumer/object-doctrine-mapping.example.yaml',
        'resources/consumer/object-schema-mirror.example.yaml',
        'resources/consumer/object-exposing-bridge.example.yaml',
        'field_pack_foundation_only: true',
        'object_title_canonical: true',
        'legacy_free: true',
        'backend_runtime_owner: true',
        'exposing_separated: true',
        'embeddable_initialization_safe',
        'doctrine_mapping_contract_ready',
        'schema_mirror_contract_ready',
        'exposing_bridge_contract_ready',
        'objecting_rc_marker',
        'backend_component_migration',
        'exposing_api_contract',
        'closure_readiness:',
        'status: ready',
    ] as $marker) {
        if (!str_contains($closure, $marker)) {
            $errors[] = 'Objecting release closure example is missing marker: ' . $marker;
        }
    }
}

$surfaceFile = $root . '/src/Surface/ObjectPackageSurface.php';
if (is_file($surfaceFile)) {
    $surface = file_get_contents($surfaceFile) ?: '';
    foreach (['RELEASE_CLOSURE_EXAMPLE', 'RELEASE_CLOSURE_DOC', 'RELEASE_CLOSURE_CHECK', 'EXPOSING_BRIDGE_EXAMPLE', 'EXPOSING_BRIDGE_DOC', 'EXPOSING_BRIDGE_CHECK', 'RC_STABILIZATION_EXAMPLE', 'RC_STABILIZATION_DOC', 'RC_STABILIZATION_CHECK'] as $constant) {
        if (!str_contains($surface, 'public const ' . $constant)) {
            $errors[] = 'ObjectPackageSurface is missing release-closure constant: ' . $constant;
        }
    }
}

if ($errors !== []) {
    echo "Objecting release closure check failed:\n";
    foreach ($errors as $error) {
        echo ' - ' . $error . "\n";
    }
    exit(1);
}

echo "Objecting release closure check passed.\n";
