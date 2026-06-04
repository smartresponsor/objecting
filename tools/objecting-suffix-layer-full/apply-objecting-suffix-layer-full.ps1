param(
    [string]$RootPath = (Get-Location).Path
)

$ErrorActionPreference = 'Stop'

Write-Host "Applying Objecting suffix/layer canon touched files to: $RootPath"

$obsoleteFiles = @(
    'src\Embeddable\ObjectEmbeddableFactory.php',
    'src\ServiceInterface\Doctrine\ObjectDoctrineMappingContractReporterInterface.php',
    'src\ServiceInterface\Exposing\ObjectExposingBridgeContractReporterInterface.php',
    'src\ServiceInterface\FieldPack\ObjectBackendAdoptionManifestReporterInterface.php',
    'src\ServiceInterface\FieldPack\ObjectBackendAdoptionPacketManifestReporterInterface.php',
    'src\ServiceInterface\FieldPack\ObjectBackendCloneCleanupContractReporterInterface.php',
    'src\ServiceInterface\FieldPack\ObjectBackendHandoffManifestReporterInterface.php',
    'src\ServiceInterface\FieldPack\ObjectBackendImportContractReporterInterface.php',
    'src\ServiceInterface\FieldPack\ObjectBackendMigrationCommandPacketReporterInterface.php',
    'src\ServiceInterface\FieldPack\ObjectBackendMigrationReadinessReporterInterface.php',
    'src\ServiceInterface\FieldPack\ObjectFieldPackConsumerContractResolverInterface.php',
    'src\ServiceInterface\FieldPack\ObjectFieldPackProfileRegistryInterface.php',
    'src\ServiceInterface\FieldPack\ObjectFieldPackRegistryInterface.php',
    'src\ServiceInterface\FieldPack\ObjectSiblingPilotMigrationHandoffReporterInterface.php',
    'src\ServiceInterface\Release\ObjectMigrationTransitionFreezeManifestReporterInterface.php',
    'src\ServiceInterface\Release\ObjectPlatformConstraintReporterInterface.php',
    'src\ServiceInterface\Release\ObjectRc2MarkerManifestReporterInterface.php',
    'src\ServiceInterface\Release\ObjectRcMarkerManifestReporterInterface.php',
    'src\ServiceInterface\Release\ObjectRcStabilizationManifestReporterInterface.php',
    'src\ServiceInterface\Release\ObjectReleaseClosureManifestReporterInterface.php',
    'src\ServiceInterface\Release\ObjectReleaseManifestReporterInterface.php',
    'src\ServiceInterface\Schema\ObjectSchemaMirrorContractReporterInterface.php',
    'src\ServiceInterface\Title\ObjectTitleAliasProfileRegistryInterface.php',
    'src\ServiceInterface\Title\ObjectTitleAliasResolverInterface.php',
    'src\ServiceInterface\Title\ObjectTitleNormalizerInterface.php',
    'src\Service\Doctrine\ObjectDoctrineMappingContractReporter.php',
    'src\Service\Exposing\ObjectExposingBridgeContractReporter.php',
    'src\Service\FieldPack\ObjectBackendAdoptionManifestReporter.php',
    'src\Service\FieldPack\ObjectBackendAdoptionPacketManifestReporter.php',
    'src\Service\FieldPack\ObjectBackendCloneCleanupContractReporter.php',
    'src\Service\FieldPack\ObjectBackendHandoffManifestReporter.php',
    'src\Service\FieldPack\ObjectBackendImportContractReporter.php',
    'src\Service\FieldPack\ObjectBackendMigrationCommandPacketReporter.php',
    'src\Service\FieldPack\ObjectBackendMigrationReadinessReporter.php',
    'src\Service\FieldPack\ObjectFieldPackConsumerContractResolver.php',
    'src\Service\FieldPack\ObjectFieldPackProfileRegistry.php',
    'src\Service\FieldPack\ObjectFieldPackRegistry.php',
    'src\Service\FieldPack\ObjectSiblingPilotMigrationHandoffReporter.php',
    'src\Service\Release\ObjectMigrationTransitionFreezeManifestReporter.php',
    'src\Service\Release\ObjectPlatformConstraintReporter.php',
    'src\Service\Release\ObjectRc2MarkerManifestReporter.php',
    'src\Service\Release\ObjectRcMarkerManifestReporter.php',
    'src\Service\Release\ObjectRcStabilizationManifestReporter.php',
    'src\Service\Release\ObjectReleaseClosureManifestReporter.php',
    'src\Service\Release\ObjectReleaseManifestReporter.php',
    'src\Service\Schema\ObjectSchemaMirrorContractReporter.php',
    'src\Service\Title\ObjectTitleAliasProfileRegistry.php',
    'src\Service\Title\ObjectTitleAliasResolver.php',
    'src\Service\Title\ObjectTitleNormalizer.php',
)

foreach ($relativePath in $obsoleteFiles) {
    $path = Join-Path $RootPath $relativePath
    if (Test-Path $path) {
        Remove-Item $path -Force
        Write-Host "Deleted obsolete file: $relativePath"
    }
}

Write-Host "Touched files are overlay files. Copy archive contents over the repository root, then run composer dump-autoload/test:quality."
