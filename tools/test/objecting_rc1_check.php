<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];

$requiredFiles = [
    'src/ValueObject/ObjectRcMarkerManifest.php',
    'src/ValueObject/ObjectRcMarkerReport.php',
    'src/Service/Release/ObjectRcMarkerManifestReporter.php',
    'src/ServiceInterface/Release/ObjectRcMarkerManifestReporterInterface.php',
    'tests/Unit/ObjectRcMarkerManifestReporterTest.php',
    'resources/release/objecting-rc1.example.yaml',
    'docs/release/objecting-rc1.md',
    'resources/release/objecting-platform-constraints.example.yaml',
    'docs/integration/objecting-platform-constraints.md',
    'tools/test/objecting_rc1_check.php',
    'resources/release/objecting-rc-stabilization.example.yaml',
    'resources/release/objecting-release-closure.example.yaml',
    'resources/release/objecting-release-manifest.example.yaml',
    'resources/consumer/object-backend-import.example.yaml',
    'resources/consumer/object-backend-adoption-packet.example.yaml',
    'resources/consumer/object-exposing-bridge.example.yaml',
    'resources/consumer/object-schema-mirror.example.yaml',
    'resources/consumer/object-doctrine-mapping.example.yaml',
];

foreach ($requiredFiles as $requiredFile) {
    if (!is_file($root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $requiredFile))) {
        $errors[] = 'Missing Objecting RC1 file: ' . $requiredFile;
    }
}

$servicesFile = $root . '/config/services.yaml';
if (is_file($servicesFile)) {
    $services = file_get_contents($servicesFile) ?: '';
    if (!str_contains($services, 'App\\Objecting\\ServiceInterface\\Release\\ObjectRcMarkerManifestReporterInterface:')) {
        $errors[] = 'config/services.yaml is missing ObjectRcMarkerManifestReporterInterface alias.';
    }
}

$composerFile = $root . '/composer.json';
if (is_file($composerFile)) {
    $composer = json_decode((string) file_get_contents($composerFile), true);
    if (!is_array($composer)) {
        $errors[] = 'composer.json is not valid JSON.';
    } else {
        foreach (['test:rc', 'test:rc1', 'test:rc-stabilization', 'test:release-closure', 'test:backend-import', 'test:platform-constraints', 'test:quality'] as $script) {
            if (!array_key_exists($script, $composer['scripts'] ?? [])) {
                $errors[] = 'composer.json is missing script: ' . $script;
            }
        }
        $qualityScript = $composer['scripts']['test:quality'] ?? [];
        $qualityScriptText = is_array($qualityScript) ? implode("\n", $qualityScript) : (string) $qualityScript;
        if (!str_contains($qualityScriptText, '@test:rc')) {
            $errors[] = 'composer test:quality must include @test:rc.';
        }
        if (!str_contains($qualityScriptText, '@test:platform-constraints')) {
            $errors[] = 'composer test:quality must include @test:platform-constraints.';
        }
    }
}

$manifestFile = $root . '/resources/release/objecting-rc1.example.yaml';
if (is_file($manifestFile)) {
    $manifest = file_get_contents($manifestFile) ?: '';
    foreach ([
        'object_rc_marker_version: 1',
        'rc_name: objecting_rc1',
        'rc_candidate: objecting_wave20_platform_constraints',
        'name: smart-responsor/objecting',
        'namespace_prefix: App\\Objecting\\',
        'bundle_class: App\\Objecting\\ObjectBundle',
        'cumulative_archive: objecting_wave20_platform_constraints_cumulative.zip',
        'touched_archive: objecting_wave20_platform_constraints_touched.zip',
        'apply_script: apply_objecting_wave20_platform_constraints_touched.ps1',
        'rc_stabilization: resources/release/objecting-rc-stabilization.example.yaml',
        'release_closure: resources/release/objecting-release-closure.example.yaml',
        'release_readiness: resources/release/objecting-release-manifest.example.yaml',
        'backend_import: resources/consumer/object-backend-import.example.yaml',
        'adoption_packet: resources/consumer/object-backend-adoption-packet.example.yaml',
        'exposing_bridge: resources/consumer/object-exposing-bridge.example.yaml',
        'schema_mirror: resources/consumer/object-schema-mirror.example.yaml',
        'doctrine_mapping: resources/consumer/object-doctrine-mapping.example.yaml',
        'platform_constraints: resources/release/objecting-platform-constraints.example.yaml',
        'composer dump-autoload',
        'composer test:quality',
        'composer test:rc',
        'composer test:platform-constraints',
        'php tools/test/objecting_rc1_check.php',
        'php tools/test/objecting_platform_constraint_check.php',
        'test:rc',
        'test:rc1',
        'test:rc-stabilization',
        'test:release-closure',
        'test:backend-import',
        'tools/test/objecting_rc1_check.php',
        'tools/test/objecting_rc_stabilization_check.php',
        'tools/test/objecting_release_closure_check.php',
        'tools/test/objecting_backend_import_contract_check.php',
        'tools/test/objecting_exposing_bridge_contract_check.php',
        'tools/test/objecting_schema_mirror_contract_check.php',
        'tools/test/objecting_doctrine_mapping_contract_check.php',
        'tools/test/objecting_platform_constraint_check.php',
        'field_pack_foundation_only: true',
        'object_title_canonical: true',
        'legacy_free: true',
        'backend_runtime_owner: true',
        'exposing_separated: true',
        'mixed_symfony_7_8_forbidden: true',
        'symfony_7_forbidden: true',
        'symfony_8_only: true',
        'php_84_only: true',
        'rc_marker:',
        'status: ready',
        'rc_accepted: true',
        'backend_component_migration',
        'exposing_api_contract',
    ] as $marker) {
        if (!str_contains($manifest, $marker)) {
            $errors[] = 'Objecting RC1 marker is missing marker: ' . $marker;
        }
    }
}

$surfaceFile = $root . '/src/ValueObject/ObjectPackageSurface.php';
if (is_file($surfaceFile)) {
    $surface = file_get_contents($surfaceFile) ?: '';
    foreach (['RC_MARKER_EXAMPLE', 'RC_MARKER_DOC', 'RC_MARKER_CHECK'] as $constant) {
        if (!str_contains($surface, 'public const ' . $constant)) {
            $errors[] = 'ObjectPackageSurface is missing RC marker constant: ' . $constant;
        }
    }
}

$readmeFile = $root . '/README.md';
if (is_file($readmeFile)) {
    $readme = file_get_contents($readmeFile) ?: '';
    foreach (['Objecting RC1', 'composer test:rc', 'resources/release/objecting-rc1.example.yaml'] as $marker) {
        if (!str_contains($readme, $marker)) {
            $errors[] = 'README is missing RC1 marker: ' . $marker;
        }
    }
}

if ($errors !== []) {
    echo "Objecting RC1 check failed:\n";
    foreach ($errors as $error) {
        echo ' - ' . $error . "\n";
    }
    exit(1);
}

echo "Objecting RC1 check passed.\n";
