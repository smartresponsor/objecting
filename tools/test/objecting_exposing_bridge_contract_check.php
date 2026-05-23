<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];
$requiredFiles = [
    'src/ValueObject/ObjectExposingBridgeContract.php',
    'src/ValueObject/ObjectExposingBridgeReport.php',
    'src/Service/Exposing/ObjectExposingBridgeContractReporter.php',
    'src/ServiceInterface/Exposing/ObjectExposingBridgeContractReporterInterface.php',
    'tests/Unit/ObjectExposingBridgeContractReporterTest.php',
    'resources/consumer/object-exposing-bridge.example.yaml',
    'docs/integration/objecting-exposing-bridge-contract.md',
];
foreach ($requiredFiles as $requiredFile) {
    if (!is_file($root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $requiredFile))) {
        $errors[] = 'Missing Exposing bridge contract file: ' . $requiredFile;
    }
}
$servicesFile = $root . '/config/services.yaml';
if (is_file($servicesFile)) {
    $services = file_get_contents($servicesFile) ?: '';
    if (!str_contains($services, 'App\\Objecting\\ServiceInterface\\Exposing\\ObjectExposingBridgeContractReporterInterface:')) {
        $errors[] = 'config/services.yaml is missing ObjectExposingBridgeContractReporterInterface alias.';
    }
}
$composerFile = $root . '/composer.json';
if (is_file($composerFile)) {
    $composer = json_decode((string) file_get_contents($composerFile), true);
    if (!is_array($composer)) { $errors[] = 'composer.json is not valid JSON.'; }
    else {
        foreach (['test:exposing-bridge', 'test:quality'] as $script) {
            if (!array_key_exists($script, $composer['scripts'] ?? [])) { $errors[] = 'composer.json is missing script: ' . $script; }
        }
        $qualityScript = $composer['scripts']['test:quality'] ?? [];
        $qualityScriptText = is_array($qualityScript) ? implode("\n", $qualityScript) : (string) $qualityScript;
        if (!str_contains($qualityScriptText, '@test:exposing-bridge')) { $errors[] = 'composer test:quality must include @test:exposing-bridge.'; }
    }
}
$exampleFile = $root . '/resources/consumer/object-exposing-bridge.example.yaml';
if (is_file($exampleFile)) {
    $example = file_get_contents($exampleFile) ?: '';
    foreach (['object_exposing_bridge_contract_version: 1','component: Paging','business_stem: Page','namespace: App\\Paging','class: App\\Paging\\Entity\\Page','package: smart-responsor/objecting','field_pack_contract: resources/objecting/Page/object-field-packs.yaml','doctrine_mapping_contract: resources/objecting/Page/object-doctrine-mapping.yaml','schema_mirror_contract: resources/schema/Page/object-schema-mirror.yaml','backend_adoption_packet: resources/objecting/Page/object-backend-adoption-packet.yaml','title_alias_profile: object_title_content','owns_field_packs: true','owns_runtime: true','repository: Exposing','openapi_contract: contract/component/Paging/Page/page.openapi.yaml','schema_mirror: contract/component/Paging/Page/page.db-schema.yaml','openapi_schema_name: PageResponse','owns_api_contract: true','- object_identity','- object_audit','- object_title','composer test:exposing-bridge','bridge_informational: true','objecting_not_api_owner: true','exposing_not_runtime_owner: true','exposing_bridge_readiness:','status: ready','exposing_bridge_backend_namespace','exposing_bridge_exposing_api_contract_owner','exposing_bridge_informational_only'] as $marker) {
        if (!str_contains($example, $marker)) { $errors[] = 'Exposing bridge example is missing marker: ' . $marker; }
    }
}
$surfaceFile = $root . '/src/ValueObject/ObjectPackageSurface.php';
if (is_file($surfaceFile)) {
    $surface = file_get_contents($surfaceFile) ?: '';
    foreach (['EXPOSING_BRIDGE_EXAMPLE', 'EXPOSING_BRIDGE_DOC', 'EXPOSING_BRIDGE_CHECK'] as $constant) {
        if (!str_contains($surface, 'public const ' . $constant)) { $errors[] = 'ObjectPackageSurface is missing Exposing bridge constant: ' . $constant; }
    }
}
$releaseClosureFile = $root . '/resources/release/objecting-release-closure.example.yaml';
if (is_file($releaseClosureFile)) {
    $releaseClosure = file_get_contents($releaseClosureFile) ?: '';
    foreach (['objecting_wave18_rc_stabilization','objecting_wave18_rc_stabilization_cumulative.zip','objecting_wave18_rc_stabilization_touched.zip','composer test:exposing-bridge','resources/consumer/object-exposing-bridge.example.yaml','docs/integration/objecting-exposing-bridge-contract.md','exposing_bridge_contract_ready'] as $marker) {
        if (!str_contains($releaseClosure, $marker)) { $errors[] = 'Release closure example is missing Exposing bridge marker: ' . $marker; }
    }
}
if ($errors !== []) {
    echo "Objecting Exposing bridge contract check failed:\n";
    foreach ($errors as $error) { echo ' - ' . $error . "\n"; }
    exit(1);
}
echo "Objecting Exposing bridge contract check passed.\n";
