<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];
$requiredFiles = [
    'src/ObjectBundle.php',
    'src/DependencyInjection/ObjectExtension.php',
    'config/services.yaml',
    'docs/integration/symfony-package-installation.md',
    'docs/integration/objecting-package-quality-gates.md',
    'docs/integration/objecting-embeddable-initialization.md',
    'src/Surface/ObjectPackageSurface.php',
    'src/Report/ObjectBackendMigrationReadinessReport.php',
    'src/Manifest/ObjectBackendAdoptionManifest.php',
    'src/Report/ObjectBackendAdoptionReport.php',
    'src/Manifest/ObjectBackendHandoffManifest.php',
    'src/Report/ObjectBackendHandoffReport.php',
    'src/Manifest/ObjectBackendAdoptionPacketManifest.php',
    'src/Report/ObjectBackendAdoptionPacketReport.php',
    'src/Contract/ObjectBackendImportContract.php',
    'src/Report/ObjectBackendImportReport.php',
    'src/Contract/ObjectBackendCloneCleanupContract.php',
    'src/Report/ObjectBackendCloneCleanupReport.php',
    'src/Packet/ObjectBackendMigrationCommandPacket.php',
    'src/Report/ObjectBackendMigrationCommandReport.php',
    'src/Manifest/ObjectReleaseManifest.php',
    'src/Report/ObjectReleaseReport.php',
    'src/Manifest/ObjectReleaseClosureManifest.php',
    'src/Report/ObjectReleaseClosureReport.php',
    'src/Manifest/ObjectRcStabilizationManifest.php',
    'src/Report/ObjectRcStabilizationReport.php',
    'src/Manifest/ObjectRcMarkerManifest.php',
    'src/Report/ObjectRcMarkerReport.php',
    'src/Manifest/ObjectRc2MarkerManifest.php',
    'src/Report/ObjectRc2MarkerReport.php',
    'src/Reporter/FieldPack/ObjectBackendMigrationReadinessReporter.php',
    'src/Reporter/FieldPack/ObjectBackendAdoptionManifestReporter.php',
    'src/Reporter/FieldPack/ObjectBackendHandoffManifestReporter.php',
    'src/Reporter/FieldPack/ObjectBackendAdoptionPacketManifestReporter.php',
    'src/Reporter/FieldPack/ObjectBackendImportContractReporter.php',
    'src/Reporter/FieldPack/ObjectBackendCloneCleanupContractReporter.php',
    'src/Reporter/FieldPack/ObjectBackendMigrationCommandPacketReporter.php',
    'src/Reporter/Release/ObjectReleaseManifestReporter.php',
    'src/Reporter/Release/ObjectReleaseClosureManifestReporter.php',
    'src/Reporter/Release/ObjectRcStabilizationManifestReporter.php',
    'src/Reporter/Release/ObjectRcMarkerManifestReporter.php',
    'src/Reporter/Release/ObjectRc2MarkerManifestReporter.php',
    'src/ReporterInterface/FieldPack/ObjectBackendMigrationReadinessReporterInterface.php',
    'src/ReporterInterface/FieldPack/ObjectBackendAdoptionManifestReporterInterface.php',
    'src/ReporterInterface/FieldPack/ObjectBackendHandoffManifestReporterInterface.php',
    'src/ReporterInterface/FieldPack/ObjectBackendAdoptionPacketManifestReporterInterface.php',
    'src/ReporterInterface/FieldPack/ObjectBackendImportContractReporterInterface.php',
    'src/ReporterInterface/FieldPack/ObjectBackendCloneCleanupContractReporterInterface.php',
    'src/ReporterInterface/FieldPack/ObjectBackendMigrationCommandPacketReporterInterface.php',
    'src/ReporterInterface/Release/ObjectReleaseManifestReporterInterface.php',
    'src/ReporterInterface/Release/ObjectReleaseClosureManifestReporterInterface.php',
    'src/ReporterInterface/Release/ObjectRcStabilizationManifestReporterInterface.php',
    'src/ReporterInterface/Release/ObjectRcMarkerManifestReporterInterface.php',
    'src/ReporterInterface/Release/ObjectRc2MarkerManifestReporterInterface.php',
    'tests/Unit/ObjectPackageSurfaceTest.php',
    'tests/Unit/ObjectBackendMigrationReadinessReporterTest.php',
    'tests/Unit/ObjectBackendAdoptionManifestReporterTest.php',
    'tests/Unit/ObjectBackendHandoffManifestReporterTest.php',
    'tests/Unit/ObjectBackendAdoptionPacketManifestReporterTest.php',
    'tests/Unit/ObjectBackendImportContractReporterTest.php',
    'tests/Unit/ObjectBackendCloneCleanupContractReporterTest.php',
    'tests/Unit/ObjectBackendMigrationCommandPacketReporterTest.php',
    'tests/Unit/ObjectReleaseManifestReporterTest.php',
    'tests/Unit/ObjectReleaseClosureManifestReporterTest.php',
    'tests/Unit/ObjectRcStabilizationManifestReporterTest.php',
    'tests/Unit/ObjectRcMarkerManifestReporterTest.php',
    'tests/Unit/ObjectRc2MarkerManifestReporterTest.php',
    'tools/test/objecting_package_surface_check.php',
    'tools/test/objecting_backend_migration_readiness_check.php',
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
    'tools/test/objecting_systemic_field_pack_check.php',
    'docs/integration/objecting-systemic-field-packs.md',
    'src/Contract/ObjectDoctrineMappingContract.php',
    'src/Report/ObjectDoctrineMappingReport.php',
    'src/Reporter/Doctrine/ObjectDoctrineMappingContractReporter.php',
    'src/ReporterInterface/Doctrine/ObjectDoctrineMappingContractReporterInterface.php',
    'tests/Unit/ObjectDoctrineMappingContractReporterTest.php',
    'resources/consumer/object-doctrine-mapping.example.yaml',
    'docs/integration/objecting-doctrine-mapping-contract.md',
    'tools/test/objecting_doctrine_mapping_contract_check.php',
    'src/Contract/ObjectSchemaMirrorContract.php',
    'src/Report/ObjectSchemaMirrorReport.php',
    'src/Contract/ObjectExposingBridgeContract.php',
    'src/Report/ObjectExposingBridgeReport.php',
    'src/Reporter/Schema/ObjectSchemaMirrorContractReporter.php',
    'src/Reporter/Exposing/ObjectExposingBridgeContractReporter.php',
    'src/ReporterInterface/Schema/ObjectSchemaMirrorContractReporterInterface.php',
    'src/ReporterInterface/Exposing/ObjectExposingBridgeContractReporterInterface.php',
    'tests/Unit/ObjectSchemaMirrorContractReporterTest.php',
    'tests/Unit/ObjectExposingBridgeContractReporterTest.php',
    'resources/consumer/object-schema-mirror.example.yaml',
    'resources/consumer/object-exposing-bridge.example.yaml', 'resources/consumer/object-backend-import.example.yaml',
    'docs/integration/objecting-schema-mirror-contract.md',
    'docs/integration/objecting-exposing-bridge-contract.md', 'docs/integration/objecting-backend-import-contract.md',
    'docs/integration/objecting-backend-clone-cleanup-contract.md',
    'docs/integration/objecting-backend-migration-command-packet.md',
    'tools/test/objecting_schema_mirror_contract_check.php',
    'tools/test/objecting_exposing_bridge_contract_check.php',
    'src/Embeddable/ObjectStateEmbeddable.php',
    'src/Embeddable/ObjectSourceEmbeddable.php',
    'src/Embeddable/ObjectFingerprintEmbeddable.php',
    'src/Embeddable/ObjectScopeEmbeddable.php',
    'src/EntityTrait/Embeddable/ObjectStateEmbeddableTrait.php',
    'src/EntityTrait/Embeddable/ObjectSourceEmbeddableTrait.php',
    'src/EntityTrait/Embeddable/ObjectFingerprintEmbeddableTrait.php',
    'src/EntityTrait/Embeddable/ObjectScopeEmbeddableTrait.php',
    'src/EntityInterface/ObjectStatefulInterface.php',
    'src/EntityInterface/ObjectSourcedInterface.php',
    'src/EntityInterface/ObjectFingerprintedInterface.php',
    'src/EntityInterface/ObjectScopedInterface.php',
    'resources/field-pack/object-state.yaml',
    'resources/field-pack/object-source.yaml',
    'resources/field-pack/object-fingerprint.yaml',
    'resources/field-pack/object-scope.yaml',
    'resources/field-pack/profile/object-systemic.yaml',
    'src/ValueObject/ObjectFieldPackName.php',
    'src/ValueObject/ObjectFieldPackProfile.php',
    'src/ValueObject/ObjectFieldPackProfileName.php',
    'src/Contract/ObjectFieldPackConsumerContract.php',
    'src/Contract/ObjectResolvedFieldPackConsumerContract.php',
    'src/Resolver/FieldPack/ObjectFieldPackConsumerContractResolver.php',
    'src/ResolverInterface/FieldPack/ObjectFieldPackConsumerContractResolverInterface.php',
    'docs/integration/object-field-pack-contract-resolution.md',
    'src/ValueObject/ObjectTitleAliasProfile.php',
    'src/ValueObject/ObjectTitleAliasProfileName.php',
    'src/Registry/FieldPack/ObjectFieldPackProfileRegistry.php',
    'src/Registry/Title/ObjectTitleAliasProfileRegistry.php',
    'src/RegistryInterface/FieldPack/ObjectFieldPackProfileRegistryInterface.php',
    'src/RegistryInterface/Title/ObjectTitleAliasProfileRegistryInterface.php',
    'src/NormalizerInterface/Title/ObjectTitleNormalizerInterface.php',
    'resources/field-pack/profile/object-baseline.yaml',
    'resources/title-alias/manifest.yaml',
    'resources/title-alias/profile/object-title-content.yaml',
    'resources/consumer/object-field-packs.example.yaml',
    'resources/consumer/object-backend-migration-readiness.example.yaml',
    'resources/consumer/object-backend-adoption.example.yaml',
    'resources/consumer/object-backend-handoff.example.yaml',
    'resources/consumer/object-backend-adoption-packet.example.yaml',
    'resources/consumer/object-backend-import.example.yaml',
    'resources/consumer/object-backend-clone-cleanup.example.yaml',
    'resources/consumer/object-backend-migration-command.example.yaml',
    'resources/release/objecting-release-manifest.example.yaml',
    'resources/release/objecting-release-closure.example.yaml',
    'resources/release/objecting-rc-stabilization.example.yaml', 'resources/release/objecting-rc1.example.yaml', 'resources/release/objecting-rc2.example.yaml', 'resources/release/objecting-platform-constraints.example.yaml', 'docs/integration/objecting-rc-stabilization.md', 'docs/release/objecting-rc1.md', 'docs/release/objecting-rc2.md', 'docs/integration/objecting-embeddable-initialization.md',
    'docs/field-pack/object-field-pack-profiles.md',
    'docs/field-pack/object-title-alias-profiles.md',
    'docs/integration/backend-component-field-pack-contract.md',
    'docs/integration/backend-component-objecting-migration.md',
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
];
foreach ($requiredFiles as $requiredFile) {
    if (!is_file($root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $requiredFile))) {
        $errors[] = 'Missing required Objecting canon file: ' . $requiredFile;
    }
}
$forbiddenPaths = ['Api','Bridge','Cache','Events','Http','Ontology','Reaction','SDK','contrib','examples','public','src/src','docs/legacy','src/EntityTrait/Embeddable/ObjectFoundationEmbeddableTrait.php'];
foreach ($forbiddenPaths as $forbiddenPath) {
    if (file_exists($root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $forbiddenPath))) {
        $errors[] = 'Forbidden legacy path still exists: ' . $forbiddenPath;
    }
}
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
foreach ($iterator as $file) {
    if (!$file instanceof SplFileInfo || !$file->isFile()) { continue; }
    $path = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));
    if (str_contains($path, ' (2).php') || str_ends_with($path, '.deleted.txt')) {
        $errors[] = 'Forbidden duplicate/deleted artifact file: ' . $path;
    }
    if (str_ends_with($path, '.php')) {
        $content = file_get_contents($file->getPathname());
        if (!is_string($content)) { $errors[] = 'Cannot read PHP file: ' . $path; continue; }
        if (str_contains($content, 'Object' . 'Foundation' . '\\')) { $errors[] = 'Forbidden legacy ObjectFoundation namespace reference: ' . $path; }
        if (str_starts_with($path, 'src/') && !str_contains($content, 'namespace App\\Objecting\\') && !str_contains($content, 'namespace App\\Objecting;')) { $errors[] = 'Source file does not use App\\Objecting namespace: ' . $path; }
        if (preg_match('/(?:class|interface|trait)\s+(?!Object)[A-Z][A-Za-z0-9_]*/', $content) === 1) { $errors[] = 'Active PHP symbol is missing Object prefix: ' . $path; }
    }
}
$composer = $root . '/composer.json';
if (is_file($composer)) {
    $composerText = file_get_contents($composer) ?: '';
    foreach (['symfony/config', 'symfony/dependency-injection', 'symfony/http-kernel', 'symfony/yaml'] as $package) {
        if (!str_contains($composerText, '"' . $package . '"')) {
            $errors[] = 'composer.json is missing installable Symfony package dependency: ' . $package;
        }
    }
    if (!str_contains($composerText, '"App\\\\Objecting\\\\"')) {
        $errors[] = 'composer.json is missing App\\Objecting PSR-4 autoload.';
    }
}
$services = $root . '/config/services.yaml';
if (is_file($services)) {
    $servicesText = file_get_contents($services) ?: '';
    foreach (['objecting.package_dir:', 'objecting.resource_dir:', 'objecting.field_pack_manifest:', 'objecting.title_alias_manifest:'] as $forbiddenParameter) {
        if (str_contains($servicesText, $forbiddenParameter)) {
            $errors[] = 'services.yaml must not overwrite ObjectExtension runtime parameter: ' . $forbiddenParameter;
        }
    }
    foreach ([
        'App\\Objecting\\RegistryInterface\\FieldPack\\ObjectFieldPackRegistryInterface',
        'App\\Objecting\\RegistryInterface\\FieldPack\\ObjectFieldPackProfileRegistryInterface',
        'App\\Objecting\\ResolverInterface\\FieldPack\\ObjectFieldPackConsumerContractResolverInterface',
        'App\\Objecting\\ReporterInterface\\FieldPack\\ObjectBackendMigrationReadinessReporterInterface',
        'App\\Objecting\\ReporterInterface\\FieldPack\\ObjectBackendAdoptionManifestReporterInterface',
        'App\\Objecting\\ReporterInterface\\FieldPack\\ObjectBackendHandoffManifestReporterInterface',
        'App\\Objecting\\ReporterInterface\\FieldPack\\ObjectBackendImportContractReporterInterface',
        'App\\Objecting\\RegistryInterface\\Title\\ObjectTitleAliasProfileRegistryInterface',
        'App\\Objecting\\ResolverInterface\\Title\\ObjectTitleAliasResolverInterface',
        'App\\Objecting\\NormalizerInterface\\Title\\ObjectTitleNormalizerInterface',
        'App\\Objecting\\ReporterInterface\\Doctrine\\ObjectDoctrineMappingContractReporterInterface',
        'App\\Objecting\\ReporterInterface\\Schema\\ObjectSchemaMirrorContractReporterInterface',
        'App\\Objecting\\ReporterInterface\\Exposing\\ObjectExposingBridgeContractReporterInterface',
        'App\\Objecting\\ReporterInterface\\Release\\ObjectReleaseManifestReporterInterface',
        'App\\Objecting\\ReporterInterface\\Release\\ObjectReleaseClosureManifestReporterInterface',
    ] as $alias) {
        if (!str_contains($servicesText, $alias . ':')) {
            $errors[] = 'services.yaml is missing Objecting mirror interface alias: ' . $alias;
        }
    }
}
$manifest = $root . '/resources/field-pack/manifest.yaml';
if (is_file($manifest)) {
    $packs = yamlList(file_get_contents($manifest) ?: '', 'field_packs');
    foreach ($packs as $pack) {
        $decl = $root . '/resources/field-pack/' . str_replace('_', '-', $pack) . '.yaml';
        if (!is_file($decl)) { $errors[] = 'Field pack listed in manifest has no declaration file: ' . $pack; continue; }
        $yaml = file_get_contents($decl) ?: '';
        if (yamlScalar($yaml, 'nameEntity') !== $pack) { $errors[] = 'Field-pack declaration nameEntity mismatch: ' . $pack; }
        foreach (['embeddable','trait','interface'] as $key) {
            $class = yamlScalar($yaml, $key);
            if ($class === null || !str_starts_with($class, 'App\\Objecting\\')) { $errors[] = "Field pack $pack has invalid $key class."; continue; }
            $relative = 'src/' . str_replace('App\\Objecting\\', '', $class);
            $relative = str_replace('\\', '/', $relative) . '.php';
            if (!is_file($root . '/' . $relative)) { $errors[] = "Field pack $pack references missing $key class file: $relative."; }
        }
        foreach (yamlList($yaml, 'columns') as $column) { if (!str_starts_with($column, 'object_')) { $errors[] = "Field pack $pack has non-object column $column."; } }
    }
}
$titleAliasManifest = $root . '/resources/title-alias/manifest.yaml';
if (is_file($titleAliasManifest)) {
    $profiles = yamlList(file_get_contents($titleAliasManifest) ?: '', 'alias_profiles');
    foreach ($profiles as $profile) {
        $decl = $root . '/resources/title-alias/profile/' . str_replace('_', '-', $profile) . '.yaml';
        if (!is_file($decl)) { $errors[] = 'Title alias profile listed in manifest has no declaration file: ' . $profile; continue; }
        $yaml = file_get_contents($decl) ?: '';
        if (yamlScalar($yaml, 'nameEntity') !== $profile) { $errors[] = 'Title alias declaration nameEntity mismatch: ' . $profile; }
        if (yamlScalar($yaml, 'field_pack') !== 'object_title') { $errors[] = 'Title alias profile must target object_title: ' . $profile; }
        foreach (['firstTitle', 'middleTitle', 'lastTitle'] as $field) {
            if (yamlNestedScalar($yaml, 'aliases', $field) === null) { $errors[] = "Title alias profile $profile is missing $field alias."; }
        }
    }
}


$embeddableTraitFiles = glob($root . '/src/EntityTrait/Embeddable/Object*EmbeddableTrait.php') ?: [];
foreach ($embeddableTraitFiles as $traitFile) {
    $relative = str_replace($root . '/', '', $traitFile);
    $content = file_get_contents($traitFile) ?: '';
    if (preg_match('/private\s+(Object[A-Za-z0-9]+Embeddable)\s+\$(object[A-Za-z0-9]+);/', $content, $match) !== 1) {
        $errors[] = 'Embeddable trait lacks typed property: ' . $relative;
        continue;
    }
    $property = $match[2];
    $helper = $property . 'Embeddable';
    if (!str_contains($content, 'private function ' . $helper . '(): ' . $match[1])) {
        $errors[] = 'Embeddable trait lacks lazy helper: ' . $relative;
    }
    if (!str_contains($content, 'if (!isset($this->' . $property . '))')) {
        $errors[] = 'Embeddable trait lacks uninitialized-property guard: ' . $relative;
    }
    if (preg_match('/\$this->' . preg_quote($property, '/') . '->/', $content) === 1) {
        $errors[] = 'Embeddable trait dereferences typed property directly instead of lazy helper: ' . $relative;
    }
}

$consumerExample = $root . '/resources/consumer/object-field-packs.example.yaml';
if (is_file($consumerExample)) {
    $yaml = file_get_contents($consumerExample) ?: '';
    foreach (['field_pack_profile', 'effective_field_packs', 'resolution'] as $requiredConsumerKey) {
        if (!str_contains($yaml, $requiredConsumerKey . ':')) {
            $errors[] = 'Consumer example is missing contract-resolution key: ' . $requiredConsumerKey;
        }
    }
    $effectivePacks = yamlList($yaml, 'effective_field_packs');
    if ($effectivePacks === []) {
        $errors[] = 'Consumer example must declare resolved effective_field_packs.';
    }
    if (yamlScalar($yaml, 'title_alias_profile') !== null && !in_array('object_title', $effectivePacks, true)) {
        $errors[] = 'Consumer example declares title_alias_profile without object_title in effective_field_packs.';
    }
}
$readinessExample = $root . '/resources/consumer/object-backend-migration-readiness.example.yaml';
if (is_file($readinessExample)) {
    $yaml = file_get_contents($readinessExample) ?: '';
    foreach (['object_identity', 'object_audit', 'object_title'] as $baselinePack) {
        if (!str_contains($yaml, '- ' . $baselinePack)) {
            $errors[] = 'Backend migration readiness example is missing baseline field pack: ' . $baselinePack;
        }
    }
    foreach (['migration_readiness:', 'status: ready', 'component_entity_namespace', 'business_stem_entity_suffix'] as $requiredMarker) {
        if (!str_contains($yaml, $requiredMarker)) {
            $errors[] = 'Backend migration readiness example is missing marker: ' . $requiredMarker;
        }
    }
}


$backendAdoptionExample = $root . '/resources/consumer/object-backend-adoption.example.yaml';
if (is_file($backendAdoptionExample)) {
    $yaml = file_get_contents($backendAdoptionExample) ?: '';
    foreach (['object_backend_adoption_version: 1', 'namespace: App\\Paging', 'table: page', 'exposing_contract:', 'backend_ownership:', 'objecting_ownership:', 'adoption_readiness:', 'status: ready'] as $requiredMarker) {
        if (!str_contains($yaml, $requiredMarker)) {
            $errors[] = 'Backend adoption example is missing marker: ' . $requiredMarker;
        }
    }
    foreach (['object_identity', 'object_audit', 'object_title'] as $baselinePack) {
        if (!str_contains($yaml, '- ' . $baselinePack)) {
            $errors[] = 'Backend adoption example is missing baseline field pack: ' . $baselinePack;
        }
    }
}


$backendHandoffExample = $root . '/resources/consumer/object-backend-handoff.example.yaml';
if (is_file($backendHandoffExample)) {
    $yaml = file_get_contents($backendHandoffExample) ?: '';
    foreach (['object_backend_handoff_version: 1', 'component: Paging', 'business_stem: Page', 'namespace: App\\Paging', 'name: objecting/object', 'adoption_manifest: resources/objecting/Page/object-backend-adoption.yaml', 'readiness_manifest: resources/objecting/Page/object-backend-migration-readiness.yaml', 'composer dump-autoload', 'composer test:quality', 'handoff_readiness:', 'status: ready'] as $requiredMarker) {
        if (!str_contains($yaml, $requiredMarker)) {
            $errors[] = 'Backend handoff example is missing marker: ' . $requiredMarker;
        }
    }
}


$backendAdoptionPacketExample = $root . '/resources/consumer/object-backend-adoption-packet.example.yaml';
if (is_file($backendAdoptionPacketExample)) {
    $yaml = file_get_contents($backendAdoptionPacketExample) ?: '';
    foreach (['object_backend_adoption_packet_version: 1', 'component: Paging', 'business_stem: Page', 'namespace: App\\Paging', 'field_pack_contract: resources/objecting/Page/object-field-packs.yaml', 'readiness_manifest: resources/objecting/Page/object-backend-migration-readiness.yaml', 'adoption_manifest: resources/objecting/Page/object-backend-adoption.yaml', 'handoff_manifest: resources/objecting/Page/object-backend-handoff.yaml', 'release_closure_manifest: resources/release/objecting-release-closure.example.yaml', 'title_alias_profile: object_title_content', 'packet_artifacts:', 'adoption_packet_readiness:', 'status: ready'] as $requiredMarker) {
        if (!str_contains($yaml, $requiredMarker)) {
            $errors[] = 'Backend adoption packet example is missing marker: ' . $requiredMarker;
        }
    }
    foreach (['object_identity', 'object_audit', 'object_title'] as $baselinePack) {
        if (!str_contains($yaml, '- ' . $baselinePack)) {
            $errors[] = 'Backend adoption packet example is missing baseline field pack: ' . $baselinePack;
        }
    }
}




$doctrineMappingExample = $root . '/resources/consumer/object-doctrine-mapping.example.yaml';
if (is_file($doctrineMappingExample)) {
    $yaml = file_get_contents($doctrineMappingExample) ?: '';
    foreach (['object_doctrine_mapping_contract_version: 1', 'component: Paging', 'business_stem: Page', 'namespace: App\\Paging', 'class: App\\Paging\\Entity\\Page', 'table: page', 'backend_owns_migrations: true', 'field_pack_contract: resources/objecting/Page/object-field-packs.yaml', 'column_prefix_false: true', 'object_columns_prefixed: true', 'App\\Objecting\\Embeddable\\ObjectTitleEmbeddable', 'App\\Objecting\\EntityTrait\\Embeddable\\ObjectTitleEmbeddableTrait', 'php bin/console doctrine:schema:validate --skip-sync', 'mapping_readiness:', 'status: ready', 'backend_migration_ownership'] as $requiredMarker) {
        if (!str_contains($yaml, $requiredMarker)) {
            $errors[] = 'Doctrine mapping example is missing marker: ' . $requiredMarker;
        }
    }
    foreach (['object_identity', 'object_audit', 'object_title'] as $baselinePack) {
        if (!str_contains($yaml, '- ' . $baselinePack)) {
            $errors[] = 'Doctrine mapping example is missing baseline field pack: ' . $baselinePack;
        }
    }
}



$schemaMirrorExample = $root . '/resources/consumer/object-schema-mirror.example.yaml';
if (is_file($schemaMirrorExample)) {
    $yaml = file_get_contents($schemaMirrorExample) ?: '';
    foreach (['object_schema_mirror_contract_version: 1', 'component: Paging', 'business_stem: Page', 'namespace: App\\Paging', 'class: App\\Paging\\Entity\\Page', 'table: page', 'backend_owns_migrations: true', 'field_pack_contract: resources/objecting/Page/object-field-packs.yaml', 'doctrine_mapping_contract: resources/objecting/Page/object-doctrine-mapping.yaml', 'path: resources/schema/Page/object-schema-mirror.yaml', 'path: contract/component/Paging/Page/page.db-schema.yaml', 'owns_api_schema_mirror: true', 'schema_mirror_readiness:', 'status: ready', 'schema_mirror_exposing_ownership', 'schema_mirror_objecting_system_column_ownership', 'schema_mirror_informational_only'] as $requiredMarker) {
        if (!str_contains($yaml, $requiredMarker)) {
            $errors[] = 'Schema mirror example is missing marker: ' . $requiredMarker;
        }
    }
    foreach (['object_identity', 'object_audit', 'object_title'] as $baselinePack) {
        if (!str_contains($yaml, '- ' . $baselinePack)) {
            $errors[] = 'Schema mirror example is missing baseline field pack: ' . $baselinePack;
        }
    }
}


$exposingBridgeExample = $root . '/resources/consumer/object-exposing-bridge.example.yaml';
if (is_file($exposingBridgeExample)) {
    $yaml = file_get_contents($exposingBridgeExample) ?: '';
    foreach (['object_exposing_bridge_contract_version: 1', 'component: Paging', 'business_stem: Page', 'namespace: App\\Paging', 'class: App\\Paging\\Entity\\Page', 'package: objecting/object', 'field_pack_contract: resources/objecting/Page/object-field-packs.yaml', 'doctrine_mapping_contract: resources/objecting/Page/object-doctrine-mapping.yaml', 'schema_mirror_contract: resources/schema/Page/object-schema-mirror.yaml', 'backend_adoption_packet: resources/objecting/Page/object-backend-adoption-packet.yaml', 'title_alias_profile: object_title_content', 'openapi_contract: contract/component/Paging/Page/page.openapi.yaml', 'schema_mirror: contract/component/Paging/Page/page.db-schema.yaml', 'openapi_schema_name: PageResponse', 'owns_api_contract: true', 'bridge_informational: true', 'objecting_not_api_owner: true', 'exposing_not_runtime_owner: true', 'exposing_bridge_readiness:', 'status: ready', 'exposing_bridge_exposing_api_contract_owner', 'exposing_bridge_informational_only'] as $requiredMarker) {
        if (!str_contains($yaml, $requiredMarker)) { $errors[] = 'Exposing bridge example is missing marker: ' . $requiredMarker; }
    }
    foreach (['object_identity', 'object_audit', 'object_title'] as $baselinePack) {
        if (!str_contains($yaml, '- ' . $baselinePack)) { $errors[] = 'Exposing bridge example is missing baseline field pack: ' . $baselinePack; }
    }
}


$backendImportExample = $root . '/resources/consumer/object-backend-import.example.yaml';
if (is_file($backendImportExample)) {
    $yaml = file_get_contents($backendImportExample) ?: '';
    foreach (['object_backend_import_contract_version: 1', 'component: Paging', 'business_stem: Page', 'namespace: App\\Paging', 'class: App\\Paging\\Entity\\Page', 'package: objecting/object', 'adoption_packet: resources/objecting/Page/object-backend-adoption-packet.yaml', 'field_pack_contract: resources/objecting/Page/object-field-packs.yaml', 'doctrine_mapping_contract: resources/objecting/Page/object-doctrine-mapping.yaml', 'schema_mirror_contract: resources/schema/Page/object-schema-mirror.yaml', 'exposing_bridge_contract: resources/objecting/Page/object-exposing-bridge.yaml', 'title_alias_profile: object_title_content', 'backend_import_readiness:', 'status: ready', 'backend_component_namespace', 'backend_entity_class', 'backend_objecting_import_paths', 'backend_schema_mirror_path', 'objecting_system_field_owner', 'backend_import_informational_only'] as $requiredMarker) {
        if (!str_contains($yaml, $requiredMarker)) { $errors[] = 'Backend import example is missing marker: ' . $requiredMarker; }
    }
    foreach (['object_identity', 'object_audit', 'object_title'] as $baselinePack) {
        if (!str_contains($yaml, '- ' . $baselinePack)) { $errors[] = 'Backend import example is missing baseline field pack: ' . $baselinePack; }
    }
}


$backendMigrationCommandExample = $root . '/resources/consumer/object-backend-migration-command.example.yaml';
if (is_file($backendMigrationCommandExample)) {
    $yaml = file_get_contents($backendMigrationCommandExample) ?: '';
    foreach (['object_backend_migration_command_version: 1', 'source_audit: workspace-objecting-field-pack-audit.md', 'objecting_can_be_modified: false', 'exposing_can_be_modified: false', 'sibling_components_can_be_modified: true', 'pilot_components:', '- Addressing', '- Taxating', 'object_identity', 'object_audit', 'object_title', 'object_state', 'object_source', 'object_fingerprint', 'object_scope', 'id: backend-owned Doctrine primary key', 'priority', 'visibility', 'no /src/Domain/', 'no Port and Adapter pattern', 'no Symfony 7 constraints', 'migration_command_readiness:', 'status: ready'] as $requiredMarker) {
        if (!str_contains($yaml, $requiredMarker)) { $errors[] = 'Backend migration command example is missing marker: ' . $requiredMarker; }
    }
}

$releaseExample = $root . '/resources/release/objecting-release-manifest.example.yaml';
if (is_file($releaseExample)) {
    $yaml = file_get_contents($releaseExample) ?: '';
    foreach (['object_release_manifest_version: 1', 'release_candidate: objecting_wave10_release_readiness', 'name: objecting/object', 'namespace_prefix: App\\Objecting\\', 'cumulative_archive: objecting_wave10_release_readiness_cumulative.zip', 'touched_archive: objecting_wave10_release_readiness_touched.zip', 'composer test:quality', 'test:release-readiness', 'field_pack_foundation_only: true', 'legacy_free: true', 'release_readiness:', 'status: ready'] as $requiredMarker) {
        if (!str_contains($yaml, $requiredMarker)) {
            $errors[] = 'Objecting release manifest example is missing marker: ' . $requiredMarker;
        }
    }
}


$releaseClosureExample = $root . '/resources/release/objecting-release-closure.example.yaml';
if (is_file($releaseClosureExample)) {
    $yaml = file_get_contents($releaseClosureExample) ?: '';
    foreach (['object_release_closure_version: 1', 'closure_candidate: objecting_wave18_rc_stabilization', 'name: objecting/object', 'namespace_prefix: App\\Objecting\\', 'cumulative_archive: objecting_wave18_rc_stabilization_cumulative.zip', 'touched_archive: objecting_wave18_rc_stabilization_touched.zip', 'composer test:quality', 'composer test:rc-stabilization', 'composer test:embeddable-initialization', 'composer test:doctrine-mapping', 'composer test:schema-mirror', 'composer test:exposing-bridge', 'composer test:backend-import', 'resources/release/objecting-release-closure.example.yaml',
    'resources/release/objecting-rc-stabilization.example.yaml', 'resources/release/objecting-rc1.example.yaml', 'resources/release/objecting-rc2.example.yaml', 'resources/release/objecting-platform-constraints.example.yaml', 'docs/integration/objecting-rc-stabilization.md', 'docs/release/objecting-rc1.md', 'docs/release/objecting-rc2.md', 'docs/integration/objecting-embeddable-initialization.md', 'docs/integration/objecting-exposing-bridge-contract.md', 'docs/integration/objecting-backend-import-contract.md', 'resources/consumer/object-backend-adoption-packet.example.yaml', 'resources/consumer/object-exposing-bridge.example.yaml', 'resources/consumer/object-backend-import.example.yaml', 'field_pack_foundation_only: true', 'object_title_canonical: true', 'legacy_free: true', 'backend_runtime_owner: true', 'exposing_separated: true', 'embeddable_initialization_safe', 'doctrine_mapping_contract_ready', 'schema_mirror_contract_ready', 'exposing_bridge_contract_ready', 'backend_import_contract_ready', 'rc_stabilization_ready', 'objecting_rc_marker', 'backend_component_migration', 'exposing_api_contract', 'closure_readiness:', 'status: ready'] as $requiredMarker) {
        if (!str_contains($yaml, $requiredMarker)) {
            $errors[] = 'Objecting release closure example is missing marker: ' . $requiredMarker;
        }
    }
}


$rcStabilizationExample = $root . '/resources/release/objecting-rc-stabilization.example.yaml';
if (is_file($rcStabilizationExample)) {
    $yaml = file_get_contents($rcStabilizationExample) ?: '';
    foreach (['object_rc_stabilization_version: 1', 'stabilization_candidate: objecting_wave18_rc_stabilization', 'name: objecting/object', 'namespace_prefix: App\\Objecting\\', 'bundle_class: App\\Objecting\\ObjectBundle', 'release_closure: resources/release/objecting-release-closure.example.yaml', 'backend_import: resources/consumer/object-backend-import.example.yaml', 'adoption_packet: resources/consumer/object-backend-adoption-packet.example.yaml', 'exposing_bridge: resources/consumer/object-exposing-bridge.example.yaml', 'schema_mirror: resources/consumer/object-schema-mirror.example.yaml', 'doctrine_mapping: resources/consumer/object-doctrine-mapping.example.yaml', 'composer test:quality', 'php tools/test/objecting_rc_stabilization_check.php', 'test:rc-stabilization', 'field_pack_foundation_only: true', 'object_title_canonical: true', 'legacy_free: true', 'backend_runtime_owner: true', 'exposing_separated: true', 'rc_marker_pending: true', 'target: objecting_rc1', 'rc_stabilization:', 'status: ready'] as $requiredMarker) {
        if (!str_contains($yaml, $requiredMarker)) {
            $errors[] = 'Objecting RC stabilization example is missing marker: ' . $requiredMarker;
        }
    }
}



$rcMarkerExample = $root . '/resources/release/objecting-rc1.example.yaml';
if (is_file($rcMarkerExample)) {
    $yaml = file_get_contents($rcMarkerExample) ?: '';
    foreach (['object_rc_marker_version: 1', 'rc_name: objecting_rc1', 'rc_candidate: objecting_wave20_platform_constraints', 'cumulative_archive: objecting_wave20_platform_constraints_cumulative.zip', 'touched_archive: objecting_wave20_platform_constraints_touched.zip', 'apply_script: apply_objecting_wave20_platform_constraints_touched.ps1', 'rc_stabilization: resources/release/objecting-rc-stabilization.example.yaml', 'rc_marker:', 'status: ready', 'rc_accepted: true', 'backend_component_migration', 'exposing_api_contract'] as $requiredMarker) {
        if (!str_contains($yaml, $requiredMarker)) {
            $errors[] = 'RC1 marker example is missing marker: ' . $requiredMarker;
        }
    }
}

if ($errors !== []) {
    echo "Objecting structural canon report failed:\n";
    foreach ($errors as $error) { echo ' - ' . $error . "\n"; }
    exit(1);
}
echo "Objecting structural canon report passed.\n";
/** @return list<string> */
function yamlList(string $yaml, string $key): array {
    $lines = preg_split('/\R/', $yaml) ?: []; $inside = false; $values = [];
    foreach ($lines as $line) {
        if (preg_match('/^' . preg_quote($key, '/') . ':\s*$/', $line) === 1) { $inside = true; continue; }
        if ($inside && preg_match('/^[A-Za-z0-9_]+:/', $line) === 1) { break; }
        if ($inside && preg_match('/^\s*-\s*(\S+)\s*$/', $line, $m) === 1) { $values[] = $m[1]; }
    }
    return $values;
}
function yamlScalar(string $yaml, string $key): ?string {
    if (preg_match('/^' . preg_quote($key, '/') . ':\s*(.+)$/m', $yaml, $m) !== 1) { return null; }
    return trim($m[1], " \t\n\r\0\x0B\"'");
}
function yamlNestedScalar(string $yaml, string $section, string $key): ?string {
    $lines = preg_split('/\R/', $yaml) ?: []; $inside = false;
    foreach ($lines as $line) {
        if (preg_match('/^' . preg_quote($section, '/') . ':\s*$/', $line) === 1) { $inside = true; continue; }
        if ($inside && preg_match('/^[A-Za-z0-9_]+:/', $line) === 1) { break; }
        if ($inside && preg_match('/^\s+' . preg_quote($key, '/') . ':\s*(.+)$/', $line, $m) === 1) { return trim($m[1], " \t\n\r\0\x0B\"'"); }
    }
    return null;
}

