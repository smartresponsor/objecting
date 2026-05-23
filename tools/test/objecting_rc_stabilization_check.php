<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];

$requiredFiles = [
    'src/ValueObject/ObjectRcStabilizationManifest.php',
    'src/ValueObject/ObjectRcStabilizationReport.php',
    'src/Service/Release/ObjectRcStabilizationManifestReporter.php',
    'src/ServiceInterface/Release/ObjectRcStabilizationManifestReporterInterface.php',
    'tests/Unit/ObjectRcStabilizationManifestReporterTest.php',
    'resources/release/objecting-rc-stabilization.example.yaml',
    'docs/integration/objecting-rc-stabilization.md',
    'tools/test/objecting_rc_stabilization_check.php',
];

foreach ($requiredFiles as $requiredFile) {
    if (!is_file($root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $requiredFile))) {
        $errors[] = 'Missing Objecting RC stabilization file: ' . $requiredFile;
    }
}

$servicesFile = $root . '/config/services.yaml';
if (is_file($servicesFile)) {
    $services = file_get_contents($servicesFile) ?: '';
    if (!str_contains($services, 'App\\Objecting\\ServiceInterface\\Release\\ObjectRcStabilizationManifestReporterInterface:')) {
        $errors[] = 'config/services.yaml is missing ObjectRcStabilizationManifestReporterInterface alias.';
    }
}

$composerFile = $root . '/composer.json';
if (is_file($composerFile)) {
    $composer = json_decode((string) file_get_contents($composerFile), true);
    if (!is_array($composer)) {
        $errors[] = 'composer.json is not valid JSON.';
    } else {
        foreach (['test:rc-stabilization', 'test:quality', 'test:release-closure', 'test:backend-import'] as $script) {
            if (!array_key_exists($script, $composer['scripts'] ?? [])) {
                $errors[] = 'composer.json is missing script: ' . $script;
            }
        }
        $qualityScript = $composer['scripts']['test:quality'] ?? [];
        $qualityScriptText = is_array($qualityScript) ? implode("\n", $qualityScript) : (string) $qualityScript;
        if (!str_contains($qualityScriptText, '@test:rc-stabilization')) {
            $errors[] = 'composer test:quality must include @test:rc-stabilization.';
        }
    }
}

$manifestFile = $root . '/resources/release/objecting-rc-stabilization.example.yaml';
if (is_file($manifestFile)) {
    $manifest = file_get_contents($manifestFile) ?: '';
    foreach ([
        'object_rc_stabilization_version: 1',
        'stabilization_candidate: objecting_wave18_rc_stabilization',
        'name: smart-responsor/objecting',
        'namespace_prefix: App\\Objecting\\',
        'bundle_class: App\\Objecting\\ObjectBundle',
        'release_closure: resources/release/objecting-release-closure.example.yaml',
        'release_readiness: resources/release/objecting-release-manifest.example.yaml',
        'backend_import: resources/consumer/object-backend-import.example.yaml',
        'adoption_packet: resources/consumer/object-backend-adoption-packet.example.yaml',
        'exposing_bridge: resources/consumer/object-exposing-bridge.example.yaml',
        'schema_mirror: resources/consumer/object-schema-mirror.example.yaml',
        'doctrine_mapping: resources/consumer/object-doctrine-mapping.example.yaml',
        'composer dump-autoload',
        'composer test:quality',
        'php tools/test/objecting_rc_stabilization_check.php',
        'test:rc-stabilization',
        'test:release-closure',
        'test:backend-import',
        'tools/test/objecting_release_closure_check.php',
        'tools/test/objecting_backend_import_contract_check.php',
        'tools/test/objecting_exposing_bridge_contract_check.php',
        'tools/test/objecting_schema_mirror_contract_check.php',
        'tools/test/objecting_doctrine_mapping_contract_check.php',
        'tools/test/objecting_rc_stabilization_check.php',
        'field_pack_foundation_only: true',
        'object_title_canonical: true',
        'legacy_free: true',
        'backend_runtime_owner: true',
        'exposing_separated: true',
        'rc_marker_pending: true',
        'target: objecting_rc1',
        'rc_stabilization:',
        'status: ready',
    ] as $marker) {
        if (!str_contains($manifest, $marker)) {
            $errors[] = 'Objecting RC stabilization example is missing marker: ' . $marker;
        }
    }
}

$surfaceFile = $root . '/src/ValueObject/ObjectPackageSurface.php';
if (is_file($surfaceFile)) {
    $surface = file_get_contents($surfaceFile) ?: '';
    foreach (['RC_STABILIZATION_EXAMPLE', 'RC_STABILIZATION_DOC', 'RC_STABILIZATION_CHECK'] as $constant) {
        if (!str_contains($surface, 'public const ' . $constant)) {
            $errors[] = 'ObjectPackageSurface is missing RC stabilization constant: ' . $constant;
        }
    }
}

if ($errors !== []) {
    echo "Objecting RC stabilization check failed:\n";
    foreach ($errors as $error) {
        echo ' - ' . $error . "\n";
    }
    exit(1);
}

echo "Objecting RC stabilization check passed.\n";
