param(
    [string]$RootPath = (Get-Location).Path
)

$ErrorActionPreference = "Stop"

$obsoleteFiles = @(
    "src\\ValueObject\\ObjectBackendAdoptionManifest.php",
    "src\\ValueObject\\ObjectBackendAdoptionPacketManifest.php",
    "src\\ValueObject\\ObjectBackendAdoptionPacketReport.php",
    "src\\ValueObject\\ObjectBackendAdoptionReport.php",
    "src\\ValueObject\\ObjectBackendCloneCleanupContract.php",
    "src\\ValueObject\\ObjectBackendCloneCleanupReport.php",
    "src\\ValueObject\\ObjectBackendHandoffManifest.php",
    "src\\ValueObject\\ObjectBackendHandoffReport.php",
    "src\\ValueObject\\ObjectBackendImportContract.php",
    "src\\ValueObject\\ObjectBackendImportReport.php",
    "src\\ValueObject\\ObjectBackendMigrationCommandPacket.php",
    "src\\ValueObject\\ObjectBackendMigrationCommandReport.php",
    "src\\ValueObject\\ObjectBackendMigrationReadinessReport.php",
    "src\\ValueObject\\ObjectDoctrineMappingContract.php",
    "src\\ValueObject\\ObjectDoctrineMappingReport.php",
    "src\\ValueObject\\ObjectExposingBridgeContract.php",
    "src\\ValueObject\\ObjectExposingBridgeReport.php",
    "src\\ValueObject\\ObjectFieldPackConsumerContract.php",
    "src\\ValueObject\\ObjectFieldPackManifest.php",
    "src\\ValueObject\\ObjectMigrationTransitionFreezeManifest.php",
    "src\\ValueObject\\ObjectMigrationTransitionFreezeReport.php",
    "src\\ValueObject\\ObjectPackageSurface.php",
    "src\\ValueObject\\ObjectPlatformConstraintManifest.php",
    "src\\ValueObject\\ObjectPlatformConstraintReport.php",
    "src\\ValueObject\\ObjectRc2MarkerManifest.php",
    "src\\ValueObject\\ObjectRc2MarkerReport.php",
    "src\\ValueObject\\ObjectRcMarkerManifest.php",
    "src\\ValueObject\\ObjectRcMarkerReport.php",
    "src\\ValueObject\\ObjectRcStabilizationManifest.php",
    "src\\ValueObject\\ObjectRcStabilizationReport.php",
    "src\\ValueObject\\ObjectReleaseClosureManifest.php",
    "src\\ValueObject\\ObjectReleaseClosureReport.php",
    "src\\ValueObject\\ObjectReleaseManifest.php",
    "src\\ValueObject\\ObjectReleaseReport.php",
    "src\\ValueObject\\ObjectResolvedFieldPackConsumerContract.php",
    "src\\ValueObject\\ObjectSchemaMirrorContract.php",
    "src\\ValueObject\\ObjectSchemaMirrorReport.php",
    "src\\ValueObject\\ObjectSiblingPilotMigrationHandoffManifest.php",
    "src\\ValueObject\\ObjectSiblingPilotMigrationHandoffReport.php",
    "src\\ValueObject\\ObjectSystemTokenDecision.php"
)

foreach ($relativePath in $obsoleteFiles) {
    $path = Join-Path $RootPath $relativePath
    if (Test-Path $path) {
        Remove-Item $path -Force
        Write-Host "Deleted obsolete file: $relativePath"
    } else {
        Write-Host "Already absent: $relativePath"
    }
}

Write-Host "Objecting ValueObject suffix-layer cleanup completed."
