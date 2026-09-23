param()

$ErrorActionPreference = 'Stop'

$objectingRoot = Split-Path -Parent $PSScriptRoot
$workspaceRoot = Split-Path -Parent $objectingRoot

$results = @()

Get-ChildItem -LiteralPath $workspaceRoot -Directory | Sort-Object Name | ForEach-Object {
    $repo = $_.FullName
    $composer = Join-Path $repo 'composer.json'
    $src = Join-Path $repo 'src'

    if ($_.Name -eq 'Objecting' -or -not (Test-Path -LiteralPath $composer) -or -not (Test-Path -LiteralPath $src)) {
        return
    }

    $identityFiles = @()
    Get-ChildItem -LiteralPath $src -Recurse -File -Filter '*.php' -ErrorAction SilentlyContinue | ForEach-Object {
        $text = Get-Content -LiteralPath $_.FullName -Raw -ErrorAction SilentlyContinue
        if ($null -ne $text -and (
            $text.Contains('ObjectIdentityEmbeddableTrait') -or
            $text.Contains('ObjectIdentifiedInterface')
        )) {
            $identityFiles += $_.FullName.Substring($repo.Length + 1).Replace('\', '/')
        }
    }

    if ($identityFiles.Count -eq 0) {
        return
    }

    $bundlesPath = Join-Path $repo 'config\bundles.php'
    $standaloneRuntime = Test-Path -LiteralPath $bundlesPath
    $objectBundleRegistered = $false
    if ($standaloneRuntime) {
        $bundles = Get-Content -LiteralPath $bundlesPath -Raw
        $objectBundleRegistered = $bundles.Contains('App\Objecting\ObjectBundle::class') -or $bundles.Contains("'App\Objecting\ObjectBundle'") -or $bundles.Contains('"App\Objecting\ObjectBundle"')
    }

    $migrationHits = @()
    $semanticMigrationHits = @()
    $migrations = Join-Path $repo 'migrations'
    if (Test-Path -LiteralPath $migrations) {
        Get-ChildItem -LiteralPath $migrations -Recurse -File -ErrorAction SilentlyContinue | Where-Object { $_.Extension -in @('.php', '.sql') } | ForEach-Object {
            $text = Get-Content -LiteralPath $_.FullName -Raw -ErrorAction SilentlyContinue
            if ($null -eq $text) {
                return
            }

            if (
                $text -match '(?i)UNIQ_[0-9A-F]{6,}.*\((?:object_)?uuid\)' -or
                $text -match '(?i)UNIQ_[0-9A-F]{6,}.*\((?:object_)?slug\)'
            ) {
                $migrationHits += $_.FullName.Substring($repo.Length + 1).Replace('\', '/')
            }

            if (
                $text -match '(?i)uniq_.*_uuid' -or
                $text -match '(?i)uniq_.*_slug'
            ) {
                $semanticMigrationHits += $_.FullName.Substring($repo.Length + 1).Replace('\', '/')
            }
        }
    }

    $results += [ordered]@{
        repository = $_.Name
        path = $repo
        identity_files = @($identityFiles | Sort-Object -Unique)
        runtime_shape = if ($standaloneRuntime) { 'standalone' } else { 'bundle_only' }
        object_bundle_registered = $objectBundleRegistered
        hash_identity_migration_files = @($migrationHits | Sort-Object -Unique)
        semantic_identity_migration_files = @($semanticMigrationHits | Sort-Object -Unique)
        identity_migration_status = if ($migrationHits.Count -eq 0) {
            'no_hash_history'
        } elseif ($semanticMigrationHits.Count -gt 0) {
            'reconciled'
        } else {
            'historical_hash_only'
        }
    }
}

$summary = [ordered]@{
    repositories = $results.Count
    standalone_consumers = @($results | Where-Object { $_.runtime_shape -eq 'standalone' }).Count
    bundle_only_consumers = @($results | Where-Object { $_.runtime_shape -eq 'bundle_only' }).Count
    bundle_registered = @($results | Where-Object { $_.runtime_shape -eq 'standalone' -and $_.object_bundle_registered }).Count
    bundle_missing = @($results | Where-Object { $_.runtime_shape -eq 'standalone' -and -not $_.object_bundle_registered }).Count
    with_hash_identity_migrations = @($results | Where-Object { $_.hash_identity_migration_files.Count -gt 0 }).Count
    reconciled_hash_history = @($results | Where-Object { $_.identity_migration_status -eq 'reconciled' }).Count
    unresolved_hash_history = @($results | Where-Object { $_.identity_migration_status -eq 'historical_hash_only' }).Count
}

[ordered]@{
    summary = $summary
    consumers = $results
} | ConvertTo-Json -Depth 8

