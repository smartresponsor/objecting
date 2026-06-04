<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];
$requiredFiles = [
    'src/Contract/ObjectBackendImportContract.php',
    'src/Report/ObjectBackendImportReport.php',
    'src/Reporter/FieldPack/ObjectBackendImportContractReporter.php',
    'src/ReporterInterface/FieldPack/ObjectBackendImportContractReporterInterface.php',
    'tests/Unit/ObjectBackendImportContractReporterTest.php',
    'resources/consumer/object-backend-import.example.yaml',
    'docs/integration/objecting-backend-import-contract.md',
];
foreach ($requiredFiles as $requiredFile) {
    if (!is_file($root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $requiredFile))) {
        $errors[] = 'Missing backend import contract file: ' . $requiredFile;
    }
}
$servicesFile = $root . '/config/services.yaml';
if (is_file($servicesFile)) {
    $services = file_get_contents($servicesFile) ?: '';
    if (!str_contains($services, 'App\\Objecting\\ReporterInterface\\FieldPack\\ObjectBackendImportContractReporterInterface:')) {
        $errors[] = 'config/services.yaml is missing ObjectBackendImportContractReporterInterface alias.';
    }
}
$composerFile = $root . '/composer.json';
if (is_file($composerFile)) {
    $composer = json_decode((string) file_get_contents($composerFile), true);
    if (!is_array($composer)) { $errors[] = 'composer.json is not valid JSON.'; }
    else {
        foreach (['test:backend-import', 'test:quality'] as $script) {
            if (!array_key_exists($script, $composer['scripts'] ?? [])) { $errors[] = 'composer.json is missing script: ' . $script; }
        }
        $qualityScript = $composer['scripts']['test:quality'] ?? [];
        $qualityScriptText = is_array($qualityScript) ? implode("\n", $qualityScript) : (string) $qualityScript;
        if (!str_contains($qualityScriptText, '@test:backend-import')) { $errors[] = 'composer test:quality must include @test:backend-import.'; }
    }
}
$exampleFile = $root . '/resources/consumer/object-backend-import.example.yaml';
if (is_file($exampleFile)) {
    $example = file_get_contents($exampleFile) ?: '';
    foreach (['object_backend_import_contract_version: 1','component: Paging','business_stem: Page','namespace: App\\Paging','class: App\\Paging\\Entity\\Page','project_root: D:\\PhpstormProjects\\www\\Paging','package: objecting/object','adoption_packet: resources/objecting/Page/object-backend-adoption-packet.yaml','field_pack_contract: resources/objecting/Page/object-field-packs.yaml','doctrine_mapping_contract: resources/objecting/Page/object-doctrine-mapping.yaml','schema_mirror_contract: resources/schema/Page/object-schema-mirror.yaml','exposing_bridge_contract: resources/objecting/Page/object-exposing-bridge.yaml','title_alias_profile: object_title_content','composer test:quality','php tools/test/objecting_backend_import_contract_check.php','test:backend-import','backend_import_readiness:','status: ready','backend_component_namespace','backend_entity_class','backend_objecting_import_paths','backend_schema_mirror_path','objecting_system_field_owner','backend_import_informational_only'] as $marker) {
        if (!str_contains($example, $marker)) { $errors[] = 'Backend import example is missing marker: ' . $marker; }
    }
    foreach (['object_identity', 'object_audit', 'object_title'] as $baselinePack) {
        if (!str_contains($example, '- ' . $baselinePack)) { $errors[] = 'Backend import example is missing baseline field pack: ' . $baselinePack; }
    }
}
$surfaceFile = $root . '/src/Surface/ObjectPackageSurface.php';
if (is_file($surfaceFile)) {
    $surface = file_get_contents($surfaceFile) ?: '';
    foreach (['BACKEND_IMPORT_EXAMPLE', 'BACKEND_IMPORT_DOC', 'BACKEND_IMPORT_CHECK'] as $constant) {
        if (!str_contains($surface, 'public const ' . $constant)) { $errors[] = 'ObjectPackageSurface is missing backend import constant: ' . $constant; }
    }
}
$releaseClosureFile = $root . '/resources/release/objecting-release-closure.example.yaml';
if (is_file($releaseClosureFile)) {
    $releaseClosure = file_get_contents($releaseClosureFile) ?: '';
    foreach (['objecting_wave18_rc_stabilization','objecting_wave18_rc_stabilization_cumulative.zip','objecting_wave18_rc_stabilization_touched.zip','composer test:backend-import','resources/consumer/object-backend-import.example.yaml','docs/integration/objecting-backend-import-contract.md','backend_import_contract_ready'] as $marker) {
        if (!str_contains($releaseClosure, $marker)) { $errors[] = 'Release closure example is missing backend import marker: ' . $marker; }
    }
}
if ($errors !== []) {
    echo "Objecting backend import contract check failed:\n";
    foreach ($errors as $error) { echo ' - ' . $error . "\n"; }
    exit(1);
}
echo "Objecting backend import contract check passed.\n";
