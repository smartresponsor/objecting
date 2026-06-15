param(
    [Parameter(Mandatory = $false)]
    [string]$RootPath = (Get-Location).Path
)

$ErrorActionPreference = 'Stop'
$sourceRoot = $PSScriptRoot
$files = @(
    'src/Embeddable/ObjectReferenceEmbeddable.php',
    'src/ObjectBundle.php',
    'tests/Support/ObjectEmbeddableTraitUsage.php'
)

foreach ($relativePath in $files) {
    $source = Join-Path $sourceRoot $relativePath
    $target = Join-Path $RootPath $relativePath
    $targetDirectory = Split-Path -Parent $target

    if (-not (Test-Path -LiteralPath $source -PathType Leaf)) {
        throw "Patch file is missing: $source"
    }

    if (-not (Test-Path -LiteralPath $targetDirectory -PathType Container)) {
        New-Item -ItemType Directory -Path $targetDirectory -Force | Out-Null
    }

    Copy-Item -LiteralPath $source -Destination $target -Force
    Write-Host "Updated: $relativePath"
}

Write-Host 'Objecting PHPStan cleanup applied. No files were deleted.'
