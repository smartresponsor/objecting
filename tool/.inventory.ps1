[CmdletBinding()]
param()

Set-StrictMode -Version Latest
$ErrorActionPreference = 'Stop'

$root = Split-Path -Parent (Split-Path -Parent $PSScriptRoot)
Get-ChildItem -LiteralPath $root -Directory | ForEach-Object {
    if (Test-Path (Join-Path $_.FullName '.git')) {
        $dirty = @(git -C $_.FullName status --porcelain=v1 -uall)
        if ($dirty.Count -gt 0) {
            Write-Output ("{0}: {1} dirty paths" -f $_.Name, $dirty.Count)
        }
    }
}

