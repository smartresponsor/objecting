<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];

$requiredFiles = [
    'src/ValueObject/ObjectBackendAdoptionPacketManifest.php',
    'src/ValueObject/ObjectBackendAdoptionPacketReport.php',
    'src/Service/FieldPack/ObjectBackendAdoptionPacketManifestReporter.php',
    'src/ServiceInterface/FieldPack/ObjectBackendAdoptionPacketManifestReporterInterface.php',
    'tests/Unit/ObjectBackendAdoptionPacketManifestReporterTest.php',
    'resources/consumer/object-backend-adoption-packet.example.yaml',
    'docs/integration/objecting-backend-adoption-packet.md',
];

foreach ($requiredFiles as $requiredFile) {
    if (!is_file($root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $requiredFile))) {
        $errors[] = 'Missing backend adoption packet file: ' . $requiredFile;
    }
}

$servicesFile = $root . '/config/services.yaml';
if (is_file($servicesFile)) {
    $services = file_get_contents($servicesFile) ?: '';
    if (!str_contains($services, 'App\\Objecting\\ServiceInterface\\FieldPack\\ObjectBackendAdoptionPacketManifestReporterInterface:')) {
        $errors[] = 'config/services.yaml is missing ObjectBackendAdoptionPacketManifestReporterInterface alias.';
    }
}

$composerFile = $root . '/composer.json';
if (is_file($composerFile)) {
    $composer = json_decode((string) file_get_contents($composerFile), true);
    if (!is_array($composer)) {
        $errors[] = 'composer.json is not valid JSON.';
    } else {
        foreach (['test:backend-adoption-packet', 'test:quality'] as $script) {
            if (!array_key_exists($script, $composer['scripts'] ?? [])) {
                $errors[] = 'composer.json is missing script: ' . $script;
            }
        }
        $qualityScript = $composer['scripts']['test:quality'] ?? [];
        $qualityScriptText = is_array($qualityScript) ? implode('\n', $qualityScript) : (string) $qualityScript;
        if (!str_contains($qualityScriptText, '@test:backend-adoption-packet')) {
            $errors[] = 'composer test:quality must include @test:backend-adoption-packet.';
        }
    }
}

$exampleFile = $root . '/resources/consumer/object-backend-adoption-packet.example.yaml';
if (is_file($exampleFile)) {
    $example = file_get_contents($exampleFile) ?: '';
    foreach ([
        'object_backend_adoption_packet_version: 1',
        'component: Paging',
        'business_stem: Page',
        'namespace: App\\Paging',
        'backend_project_root: D:\\PhpstormProjects\\www\\Paging',
        'name: smart-responsor/objecting',
        'constraint: ^1.0',
        'field_pack_contract: resources/objecting/Page/object-field-packs.yaml',
        'readiness_manifest: resources/objecting/Page/object-backend-migration-readiness.yaml',
        'adoption_manifest: resources/objecting/Page/object-backend-adoption.yaml',
        'handoff_manifest: resources/objecting/Page/object-backend-handoff.yaml',
        'release_closure_manifest: resources/release/objecting-release-closure.example.yaml',
        '- object_identity',
        '- object_audit',
        '- object_title',
        'title_alias_profile: object_title_content',
        'composer dump-autoload',
        'composer test:quality',
        'test:canon',
        'test:quality',
        'packet_artifacts:',
        'path: contract/component/Paging/Page/manifest.yaml',
        'standalone_ready: true',
        'adoption_packet_readiness:',
        'status: ready',
    ] as $marker) {
        if (!str_contains($example, $marker)) {
            $errors[] = 'Backend adoption packet example is missing marker: ' . $marker;
        }
    }
}

$surfaceFile = $root . '/src/ValueObject/ObjectPackageSurface.php';
if (is_file($surfaceFile)) {
    $surface = file_get_contents($surfaceFile) ?: '';
    foreach (['BACKEND_ADOPTION_PACKET_EXAMPLE', 'BACKEND_ADOPTION_PACKET_DOC', 'BACKEND_ADOPTION_PACKET_CHECK'] as $constant) {
        if (!str_contains($surface, 'public const ' . $constant)) {
            $errors[] = 'ObjectPackageSurface is missing backend adoption packet constant: ' . $constant;
        }
    }
}

$releaseClosureFile = $root . '/resources/release/objecting-release-closure.example.yaml';
if (is_file($releaseClosureFile)) {
    $releaseClosure = file_get_contents($releaseClosureFile) ?: '';
    if (!str_contains($releaseClosure, 'resources/consumer/object-backend-adoption-packet.example.yaml')) {
        $errors[] = 'Release closure example must include backend adoption packet as a consumer contract.';
    }
}

if ($errors !== []) {
    echo "Objecting backend adoption packet check failed:\n";
    foreach ($errors as $error) {
        echo ' - ' . $error . "\n";
    }
    exit(1);
}

echo "Objecting backend adoption packet check passed.\n";
