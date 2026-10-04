[CmdletBinding()]
param()

Set-StrictMode -Version Latest
$ErrorActionPreference = 'Stop'

php tools/audit/objecting_workspace_entity_kind_audit.php
if ($LASTEXITCODE -ne 0) { exit $LASTEXITCODE }

