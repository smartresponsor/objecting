<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];

$requiredFiles = [
    'composer.json',
    'config/services.yaml',
    'src/ObjectBundle.php',
    'src/DependencyInjection/ObjectExtension.php',
    'src/Surface/ObjectPackageSurface.php',
    'resources/field-pack/manifest.yaml',
    'resources/title-alias/manifest.yaml',
    'tools/test/objecting_title_alias_hardening_check.php',
    'tools/test/objecting_internal_type_resolution_check.php',
    'docs/integration/objecting-title-alias-hardening.md',
    'resources/title-alias/object-title-alias-governance.example.yaml',
    'resources/consumer/object-field-packs.example.yaml',
    'resources/consumer/object-backend-migration-readiness.example.yaml',
    'resources/consumer/object-backend-adoption.example.yaml',
    'resources/consumer/object-backend-handoff.example.yaml',
    'resources/consumer/object-backend-adoption-packet.example.yaml',
    'resources/consumer/object-backend-import.example.yaml',
    'resources/consumer/object-backend-clone-cleanup.example.yaml',
    'resources/consumer/object-backend-migration-command.example.yaml',
    'resources/consumer/object-schema-mirror.example.yaml',
    'resources/consumer/object-exposing-bridge.example.yaml',
    'resources/release/objecting-release-manifest.example.yaml',
    'resources/release/objecting-release-closure.example.yaml',
    'resources/release/objecting-rc-stabilization.example.yaml',
    'resources/release/objecting-rc1.example.yaml',
    'resources/release/objecting-rc2.example.yaml',
    'docs/integration/symfony-package-installation.md',
    'docs/integration/objecting-backend-migration-readiness.md',
    'docs/integration/objecting-backend-adoption-manifest.md',
    'docs/integration/objecting-backend-handoff-contract.md',
    'docs/integration/objecting-backend-adoption-packet.md',
    'docs/integration/objecting-backend-import-contract.md',
    'docs/integration/objecting-backend-clone-cleanup-contract.md',
    'docs/integration/objecting-backend-migration-command-packet.md',
    'docs/integration/objecting-release-readiness.md',
    'docs/integration/objecting-release-closure.md',
    'docs/integration/objecting-rc-stabilization.md',
    'docs/release/objecting-rc1.md',
    'docs/release/objecting-rc2.md',
    'docs/integration/objecting-package-quality-gates.md',
    'docs/integration/objecting-embeddable-initialization.md',
    'docs/integration/objecting-schema-mirror-contract.md',
    'docs/integration/objecting-exposing-bridge-contract.md',
    'tools/test/objecting_backend_adoption_manifest_check.php',
    'tools/test/objecting_backend_handoff_contract_check.php',
    'tools/test/objecting_backend_adoption_packet_check.php',
    'tools/test/objecting_backend_import_contract_check.php',
    'tools/test/objecting_backend_clone_cleanup_contract_check.php',
    'tools/test/objecting_backend_migration_command_packet_check.php',
    'tools/test/objecting_release_readiness_check.php',
    'tools/test/objecting_release_closure_check.php',
    'tools/test/objecting_rc_stabilization_check.php',
    'tools/test/objecting_rc1_check.php',
    'tools/test/objecting_rc2_check.php',
    'tools/test/objecting_embeddable_initialization_check.php',
    'tools/test/objecting_schema_mirror_contract_check.php',
    'tools/test/objecting_exposing_bridge_contract_check.php',
    'docs/integration/objecting-systemic-field-packs.md',
    'tools/test/objecting_systemic_field_pack_check.php',
];

foreach ($requiredFiles as $requiredFile) {
    if (!is_file($root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $requiredFile))) {
        $errors[] = 'Missing package-surface file: ' . $requiredFile;
    }
}

$composerFile = $root . '/composer.json';
if (is_file($composerFile)) {
    $composer = json_decode((string) file_get_contents($composerFile), true);
    if (!is_array($composer)) {
        $errors[] = 'composer.json is not valid JSON.';
    } else {
        if (($composer['name'] ?? null) !== 'objecting/object') {
            $errors[] = 'composer.json package name must be objecting/object.';
        }
        if (($composer['type'] ?? null) !== 'library') {
            $errors[] = 'composer.json type must stay library.';
        }
        $autoload = $composer['autoload']['psr-4'] ?? [];
        if (($autoload['App\\Objecting\\'] ?? null) !== 'src/') {
            $errors[] = 'composer.json must expose App\\Objecting\\ => src/ autoload.';
        }
        foreach (['test:canon', 'test:internal-types', 'test:package-surface', 'test:migration-readiness', 'test:backend-adoption', 'test:backend-handoff', 'test:backend-adoption-packet', 'test:backend-import', 'test:backend-clone-cleanup', 'test:backend-migration-command', 'test:release-readiness', 'test:release-closure', 'test:rc-stabilization', 'test:rc', 'test:rc1', 'test:rc2', 'test:embeddable-initialization', 'test:schema-mirror', 'test:exposing-bridge', 'test:quality', 'phpstan', 'test'] as $script) {
            if (!array_key_exists($script, $composer['scripts'] ?? [])) {
                $errors[] = 'composer.json is missing script: ' . $script;
            }
        }
        foreach (['symfony/config', 'symfony/dependency-injection', 'symfony/http-kernel', 'symfony/yaml'] as $dependency) {
            if (!array_key_exists($dependency, $composer['require'] ?? [])) {
                $errors[] = 'composer.json is missing package-surface dependency: ' . $dependency;
            }
        }
    }
}

$servicesFile = $root . '/config/services.yaml';
if (is_file($servicesFile)) {
    $services = (string) file_get_contents($servicesFile);
    foreach (['objecting.package_dir:', 'objecting.resource_dir:', 'objecting.field_pack_manifest:', 'objecting.title_alias_manifest:'] as $forbiddenParameter) {
        if (str_contains($services, $forbiddenParameter)) {
            $errors[] = 'config/services.yaml must not overwrite ObjectExtension runtime parameter: ' . $forbiddenParameter;
        }
    }
    foreach ([
        'App\\Objecting\\RegistryInterface\\FieldPack\\ObjectFieldPackRegistryInterface:',
        'App\\Objecting\\RegistryInterface\\FieldPack\\ObjectFieldPackProfileRegistryInterface:',
        'App\\Objecting\\ResolverInterface\\FieldPack\\ObjectFieldPackConsumerContractResolverInterface:',
        'App\\Objecting\\ReporterInterface\\FieldPack\\ObjectBackendMigrationReadinessReporterInterface:',
        'App\\Objecting\\ReporterInterface\\FieldPack\\ObjectBackendAdoptionManifestReporterInterface:',
        'App\\Objecting\\ReporterInterface\\FieldPack\\ObjectBackendHandoffManifestReporterInterface:',
        'App\\Objecting\\ReporterInterface\\FieldPack\\ObjectBackendAdoptionPacketManifestReporterInterface:',
        'App\\Objecting\\ReporterInterface\\FieldPack\\ObjectBackendImportContractReporterInterface:',
        'App\\Objecting\\ReporterInterface\\FieldPack\\ObjectBackendCloneCleanupContractReporterInterface:',
        'App\\Objecting\\ReporterInterface\\FieldPack\\ObjectBackendMigrationCommandPacketReporterInterface:',
        'App\\Objecting\\RegistryInterface\\Title\\ObjectTitleAliasProfileRegistryInterface:',
        'App\\Objecting\\ResolverInterface\\Title\\ObjectTitleAliasResolverInterface:',
        'App\\Objecting\\ReporterInterface\\Schema\\ObjectSchemaMirrorContractReporterInterface:',
        'App\\Objecting\\ReporterInterface\\Exposing\\ObjectExposingBridgeContractReporterInterface:',
        'App\\Objecting\\ReporterInterface\\Release\\ObjectReleaseManifestReporterInterface:',
        'App\\Objecting\\ReporterInterface\\Release\\ObjectReleaseClosureManifestReporterInterface:',
        'App\\Objecting\\ReporterInterface\\Release\\ObjectRcStabilizationManifestReporterInterface:',
        'App\\Objecting\\ReporterInterface\\Release\\ObjectRcMarkerManifestReporterInterface:',
        'App\\Objecting\\ReporterInterface\\Release\\ObjectRc2MarkerManifestReporterInterface:',
        'App\\Objecting\\NormalizerInterface\\Title\\ObjectTitleNormalizerInterface:',
    ] as $alias) {
        if (!str_contains($services, $alias)) {
            $errors[] = 'config/services.yaml is missing mirror interface alias: ' . $alias;
        }
    }
}

$extensionFile = $root . '/src/DependencyInjection/ObjectExtension.php';
if (is_file($extensionFile)) {
    $extension = (string) file_get_contents($extensionFile);
    foreach (['objecting.package_dir', 'objecting.resource_dir', 'objecting.field_pack_manifest', 'objecting.title_alias_manifest'] as $parameter) {
        if (!str_contains($extension, $parameter)) {
            $errors[] = 'ObjectExtension must set parameter: ' . $parameter;
        }
    }
    if (!str_contains($extension, "return 'objecting';")) {
        $errors[] = 'ObjectExtension alias must stay objecting.';
    }
}

$surfaceFile = $root . '/src/Surface/ObjectPackageSurface.php';
if (is_file($surfaceFile)) {
    $surface = (string) file_get_contents($surfaceFile);
    foreach (['COMPOSER_PACKAGE', 'NAMESPACE_PREFIX', 'BUNDLE_CLASS', 'EXTENSION_CLASS', 'EXTENSION_ALIAS', 'SERVICE_CONFIG', 'BACKEND_MIGRATION_READINESS_EXAMPLE', 'BACKEND_MIGRATION_READINESS_DOC', 'BACKEND_MIGRATION_READINESS_CHECK', 'BACKEND_ADOPTION_EXAMPLE', 'BACKEND_ADOPTION_DOC', 'BACKEND_ADOPTION_CHECK', 'BACKEND_HANDOFF_EXAMPLE', 'BACKEND_HANDOFF_DOC', 'BACKEND_HANDOFF_CHECK', 'BACKEND_ADOPTION_PACKET_EXAMPLE', 'BACKEND_ADOPTION_PACKET_DOC', 'BACKEND_ADOPTION_PACKET_CHECK', 'BACKEND_IMPORT_EXAMPLE', 'BACKEND_IMPORT_DOC', 'BACKEND_IMPORT_CHECK', 'BACKEND_CLONE_CLEANUP_EXAMPLE', 'BACKEND_CLONE_CLEANUP_DOC', 'BACKEND_CLONE_CLEANUP_CHECK', 'BACKEND_MIGRATION_COMMAND_EXAMPLE', 'BACKEND_MIGRATION_COMMAND_DOC', 'BACKEND_MIGRATION_COMMAND_CHECK', 'RELEASE_MANIFEST_EXAMPLE', 'RELEASE_READINESS_DOC', 'RELEASE_READINESS_CHECK', 'RELEASE_CLOSURE_EXAMPLE', 'RELEASE_CLOSURE_DOC', 'RELEASE_CLOSURE_CHECK', 'EMBEDDABLE_INITIALIZATION_DOC', 'EMBEDDABLE_INITIALIZATION_CHECK', 'SCHEMA_MIRROR_EXAMPLE', 'SCHEMA_MIRROR_DOC', 'SCHEMA_MIRROR_CHECK', 'EXPOSING_BRIDGE_EXAMPLE', 'EXPOSING_BRIDGE_DOC', 'EXPOSING_BRIDGE_CHECK', 'RC_STABILIZATION_EXAMPLE', 'RC_STABILIZATION_DOC', 'RC_STABILIZATION_CHECK', 'RC_MARKER_EXAMPLE', 'RC_MARKER_DOC', 'RC_MARKER_CHECK', 'RC2_MARKER_EXAMPLE', 'RC2_MARKER_DOC', 'RC2_MARKER_CHECK'] as $constant) {
        if (!str_contains($surface, 'public const ' . $constant)) {
            $errors[] = 'ObjectPackageSurface is missing constant: ' . $constant;
        }
    }
}

if ($errors !== []) {
    echo "Objecting package surface check failed:\n";
    foreach ($errors as $error) {
        echo ' - ' . $error . "\n";
    }
    exit(1);
}

echo "Objecting package surface check passed.\n";
