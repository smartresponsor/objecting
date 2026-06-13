<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];

$requiredFiles = [
    'src/Manifest/ObjectRc2MarkerManifest.php',
    'src/Report/ObjectRc2MarkerReport.php',
    'src/Reporter/Release/ObjectRc2MarkerManifestReporter.php',
    'src/ReporterInterface/Release/ObjectRc2MarkerManifestReporterInterface.php',
    'tests/Unit/ObjectRc2MarkerManifestReporterTest.php',
    'resources/release/objecting-rc2.example.yaml',
    'docs/release/objecting-rc2.md',
    'tools/test/objecting_rc2_check.php',
];

foreach ($requiredFiles as $requiredFile) {
    if (!is_file($root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $requiredFile))) {
        $errors[] = 'Missing Objecting RC2 marker file: ' . $requiredFile;
    }
}

$composerFile = $root . '/composer.json';
if (is_file($composerFile)) {
    $composer = json_decode((string) file_get_contents($composerFile), true);
    if (!is_array($composer)) {
        $errors[] = 'composer.json is not valid JSON.';
    } else {
        foreach (['test:rc', 'test:rc1', 'test:rc2', 'test:quality'] as $script) {
            if (!array_key_exists($script, $composer['scripts'] ?? [])) {
                $errors[] = 'composer.json is missing script: ' . $script;
            }
        }
        $qualityScript = $composer['scripts']['test:quality'] ?? [];
        $qualityScriptText = is_array($qualityScript) ? implode("\n", $qualityScript) : (string) $qualityScript;
        if (!str_contains($qualityScriptText, '@test:rc')) {
            $errors[] = 'composer test:quality must include @test:rc.';
        }
        $rcScript = $composer['scripts']['test:rc'] ?? '';
        $rcScriptText = is_array($rcScript) ? implode("\n", $rcScript) : (string) $rcScript;
        if (!str_contains($rcScriptText, 'objecting_rc2_check.php')) {
            $errors[] = 'composer test:rc must point to objecting_rc2_check.php for the active RC baseline.';
        }
    }
}

$servicesFile = $root . '/config/services.yaml';
if (is_file($servicesFile)) {
    $services = file_get_contents($servicesFile) ?: '';
    if (!str_contains($services, 'App\\Objecting\\ReporterInterface\\Release\\ObjectRc2MarkerManifestReporterInterface:')) {
        $errors[] = 'config/services.yaml is missing ObjectRc2MarkerManifestReporterInterface alias.';
    }
}

$surfaceFile = $root . '/src/Surface/ObjectPackageSurface.php';
if (is_file($surfaceFile)) {
    $surface = file_get_contents($surfaceFile) ?: '';
    foreach (['RC2_MARKER_EXAMPLE', 'RC2_MARKER_DOC', 'RC2_MARKER_CHECK'] as $constant) {
        if (!str_contains($surface, 'public const ' . $constant)) {
            $errors[] = 'ObjectPackageSurface is missing RC2 marker constant: ' . $constant;
        }
    }
}

$rc2File = $root . '/resources/release/objecting-rc2.example.yaml';
if (is_file($rc2File)) {
    $rc2 = file_get_contents($rc2File) ?: '';
    foreach ([
        'object_rc2_marker_version: 1',
        'rc_name: objecting_rc2',
        'previous_rc_name: objecting_rc1',
        'rc_candidate: objecting_wave25_rc2_marker',
        'name: objecting/object',
        'namespace_prefix: App\\Objecting\\',
        'bundle_class: App\\Objecting\\ObjectBundle',
        'cumulative_archive: objecting_wave25_rc2_marker_cumulative.zip',
        'touched_archive: objecting_wave25_rc2_marker_touched.zip',
        'apply_script: apply_objecting_wave25_rc2_marker_touched.ps1',
        'resources/release/objecting-release-closure.example.yaml',
        'resources/field-pack/manifest.yaml',
        'resources/title-alias/manifest.yaml',
        'resources/consumer/object-backend-migration-command.example.yaml',
        'resources/consumer/object-backend-clone-cleanup.example.yaml',
        'resources/release/objecting-platform-constraints.example.yaml',
        '- object_identity',
        '- object_audit',
        '- object_title',
        '- object_state',
        '- object_source',
        '- object_fingerprint',
        '- object_scope',
        '- object_id',
        '- object_name',
        '- object_description',
        '- object_priority',
        '- object_visibility',
        '- priority',
        '- visibility',
        'composer test:rc2',
        'php tools/test/objecting_rc2_check.php',
        'field_pack_foundation_only: true',
        'object_title_canonical: true',
        'legacy_free: true',
        'backend_runtime_owner: true',
        'exposing_separated: true',
        'symfony_8_only: true',
        'php_84_only: true',
        'rc_accepted: true',
        'backend_component_migration',
        'exposing_api_contract',
    ] as $marker) {
        if (!str_contains($rc2, $marker)) {
            $errors[] = 'Objecting RC2 marker example is missing marker: ' . $marker;
        }
    }
}

$docFile = $root . '/docs/release/objecting-rc2.md';
if (is_file($docFile)) {
    $doc = file_get_contents($docFile) ?: '';
    foreach (['Objecting RC2 baseline', 'object_state', 'object_source', 'object_fingerprint', 'object_scope', 'object_title', 'object_id', 'object_priority', 'object_visibility', 'composer test:rc2'] as $marker) {
        if (!str_contains($doc, $marker)) {
            $errors[] = 'Objecting RC2 doc is missing marker: ' . $marker;
        }
    }
}

$releaseClosureFile = $root . '/resources/release/objecting-release-closure.example.yaml';
if (is_file($releaseClosureFile)) {
    $releaseClosure = file_get_contents($releaseClosureFile) ?: '';
    foreach (['objecting_wave25_rc2_marker', 'resources/release/objecting-rc2.example.yaml', 'docs/release/objecting-rc2.md', 'objecting_rc2_marker_ready'] as $marker) {
        if (!str_contains($releaseClosure, $marker)) {
            $errors[] = 'Release closure example is missing RC2 marker: ' . $marker;
        }
    }
}

if ($errors !== []) {
    echo "Objecting RC2 marker check failed:\n";
    foreach ($errors as $error) {
        echo ' - ' . $error . "\n";
    }
    exit(1);
}

echo "Objecting RC2 marker check passed.\n";

