param(
    [Parameter(Mandatory = $true)]
    [string]$RootPath,

    [switch]$Apply
)

$ErrorActionPreference = 'Stop'

$root = (Resolve-Path $RootPath).Path
$composerPath = Join-Path $root 'composer.json'

if (-not (Test-Path $composerPath -PathType Leaf)) {
    throw "composer.json not found in: $root"
}

$composer = Get-Content $composerPath -Raw | ConvertFrom-Json
if ($composer.name -ne 'objecting/object') {
    throw "Unexpected Composer package '$($composer.name)'. Expected 'objecting/object'."
}

$obsoleteFiles = @(
    # Wave 1: tenant/lifecycle cleanup
    'docs/field-pack/object-scope.md',
    'resources/field-pack/object-scope.yaml',
    'src/Embeddable/ObjectScopeEmbeddable.php',
    'src/EntityInterface/ObjectScopedInterface.php',
    'src/EntityTrait/Embeddable/ObjectScopeEmbeddableTrait.php',
    'tests/Unit/ObjectLifecycleAliasCompatibilityTest.php',

    # Wave 2: duplicate Service and ServiceInterface trees
    'src/Service/Doctrine/ObjectDoctrineMappingContractReporter.php',
    'src/Service/Exposing/ObjectExposingBridgeContractReporter.php',
    'src/Service/FieldPack/ObjectBackendAdoptionManifestReporter.php',
    'src/Service/FieldPack/ObjectBackendAdoptionPacketManifestReporter.php',
    'src/Service/FieldPack/ObjectBackendCloneCleanupContractReporter.php',
    'src/Service/FieldPack/ObjectBackendHandoffManifestReporter.php',
    'src/Service/FieldPack/ObjectBackendImportContractReporter.php',
    'src/Service/FieldPack/ObjectBackendMigrationCommandPacketReporter.php',
    'src/Service/FieldPack/ObjectBackendMigrationReadinessReporter.php',
    'src/Service/FieldPack/ObjectFieldPackConsumerContractResolver.php',
    'src/Service/FieldPack/ObjectFieldPackProfileRegistry.php',
    'src/Service/FieldPack/ObjectFieldPackRegistry.php',
    'src/Service/FieldPack/ObjectSiblingPilotMigrationHandoffReporter.php',
    'src/Service/Reference/NullObjectReferenceIntegrityChecker.php',
    'src/Service/Release/ObjectMigrationTransitionFreezeManifestReporter.php',
    'src/Service/Release/ObjectPlatformConstraintReporter.php',
    'src/Service/Release/ObjectRc2MarkerManifestReporter.php',
    'src/Service/Release/ObjectRcMarkerManifestReporter.php',
    'src/Service/Release/ObjectRcStabilizationManifestReporter.php',
    'src/Service/Release/ObjectReleaseClosureManifestReporter.php',
    'src/Service/Release/ObjectReleaseManifestReporter.php',
    'src/Service/Schema/ObjectSchemaMirrorContractReporter.php',
    'src/Service/Title/ObjectTitleAliasProfileRegistry.php',
    'src/Service/Title/ObjectTitleAliasResolver.php',
    'src/Service/Title/ObjectTitleNormalizer.php',
    'src/ServiceInterface/Doctrine/ObjectDoctrineMappingContractReporterInterface.php',
    'src/ServiceInterface/Exposing/ObjectExposingBridgeContractReporterInterface.php',
    'src/ServiceInterface/FieldPack/ObjectBackendAdoptionManifestReporterInterface.php',
    'src/ServiceInterface/FieldPack/ObjectBackendAdoptionPacketManifestReporterInterface.php',
    'src/ServiceInterface/FieldPack/ObjectBackendCloneCleanupContractReporterInterface.php',
    'src/ServiceInterface/FieldPack/ObjectBackendHandoffManifestReporterInterface.php',
    'src/ServiceInterface/FieldPack/ObjectBackendImportContractReporterInterface.php',
    'src/ServiceInterface/FieldPack/ObjectBackendMigrationCommandPacketReporterInterface.php',
    'src/ServiceInterface/FieldPack/ObjectBackendMigrationReadinessReporterInterface.php',
    'src/ServiceInterface/FieldPack/ObjectFieldPackConsumerContractResolverInterface.php',
    'src/ServiceInterface/FieldPack/ObjectFieldPackProfileRegistryInterface.php',
    'src/ServiceInterface/FieldPack/ObjectFieldPackRegistryInterface.php',
    'src/ServiceInterface/FieldPack/ObjectSiblingPilotMigrationHandoffReporterInterface.php',
    'src/ServiceInterface/Reference/ObjectReferenceIntegrityCheckerInterface.php',
    'src/ServiceInterface/Release/ObjectMigrationTransitionFreezeManifestReporterInterface.php',
    'src/ServiceInterface/Release/ObjectPlatformConstraintReporterInterface.php',
    'src/ServiceInterface/Release/ObjectRc2MarkerManifestReporterInterface.php',
    'src/ServiceInterface/Release/ObjectRcMarkerManifestReporterInterface.php',
    'src/ServiceInterface/Release/ObjectRcStabilizationManifestReporterInterface.php',
    'src/ServiceInterface/Release/ObjectReleaseClosureManifestReporterInterface.php',
    'src/ServiceInterface/Release/ObjectReleaseManifestReporterInterface.php',
    'src/ServiceInterface/Schema/ObjectSchemaMirrorContractReporterInterface.php',
    'src/ServiceInterface/Title/ObjectTitleAliasProfileRegistryInterface.php',
    'src/ServiceInterface/Title/ObjectTitleAliasResolverInterface.php',
    'src/ServiceInterface/Title/ObjectTitleNormalizerInterface.php'
)

$existing = @()
$missing = @()

foreach ($relative in $obsoleteFiles) {
    $target = Join-Path $root $relative
    if (Test-Path $target -PathType Leaf) {
        $existing += [pscustomobject]@{ Relative = $relative; Target = $target }
    } else {
        $missing += $relative
    }
}

Write-Host "Objecting root: $root"
Write-Host "Obsolete files found: $($existing.Count)"
Write-Host "Already absent: $($missing.Count)"

if ($existing.Count -eq 0) {
    Write-Host 'Nothing to delete.'
    exit 0
}

Write-Host ''
Write-Host 'Files scheduled for deletion:'
$existing.Relative | ForEach-Object { Write-Host "  $_" }

if (-not $Apply) {
    Write-Host ''
    Write-Host 'Preview only. Re-run with -Apply to delete these exact files.'
    exit 0
}

foreach ($item in $existing) {
    Remove-Item -LiteralPath $item.Target -Force
    Write-Host "Deleted: $($item.Relative)"
}

# Remove only directories that became empty; never recurse-delete non-empty directories.
$candidateDirectories = @(
    'src/Service',
    'src/ServiceInterface',
    'docs/field-pack'
)

foreach ($relativeDirectory in $candidateDirectories) {
    $directory = Join-Path $root $relativeDirectory
    if (Test-Path $directory -PathType Container) {
        $hasChildren = Get-ChildItem -LiteralPath $directory -Force -Recurse | Select-Object -First 1
        if (-not $hasChildren) {
            Remove-Item -LiteralPath $directory -Force
            Write-Host "Removed empty directory: $relativeDirectory"
        }
    }
}

Write-Host ''
Write-Host 'Deletion completed.'
Write-Host 'Run next:'
Write-Host '  composer dump-autoload'
Write-Host '  composer test:quality'
Write-Host '  composer test'
Write-Host '  composer phpstan'
