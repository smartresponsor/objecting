<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];

$requiredFiles = [
    'src/ValueObject/ObjectTitleAliasMap.php',
    'src/ValueObject/ObjectTitleAliasProfileName.php',
    'src/ValueObject/ObjectSystemTokenDecision.php',
    'src/Service/Title/ObjectTitleAliasProfileRegistry.php',
    'resources/title-alias/manifest.yaml',
    'resources/title-alias/object-title-alias-governance.example.yaml',
    'docs/integration/objecting-title-alias-hardening.md',
];
foreach ($requiredFiles as $file) {
    if (!is_file($root . '/' . $file)) {
        $errors[] = 'Missing title-alias hardening file: ' . $file;
    }
}

$aliasMap = (string) file_get_contents($root . '/src/ValueObject/ObjectTitleAliasMap.php');
foreach (['ALIAS_NAME', 'ALIAS_TITLE', 'ALIAS_DESCRIPTION', 'ALIAS_SHORT_DESCRIPTION', 'ALIAS_LABEL', 'ALIAS_DISPLAY_NAME', 'ALIAS_SUMMARY', 'ALIAS_SUBTITLE', 'canonicalFieldForAlias', 'hasAlias', 'commonBusinessAliases'] as $needle) {
    if (!str_contains($aliasMap, $needle)) {
        $errors[] = 'ObjectTitleAliasMap is missing hardening member: ' . $needle;
    }
}
if (!str_contains($aliasMap, 'Duplicate aliases')) {
    $errors[] = 'ObjectTitleAliasMap must reject duplicate alias values.';
}

$profileNames = (string) file_get_contents($root . '/src/ValueObject/ObjectTitleAliasProfileName.php');
foreach (['LABEL', 'DISPLAY'] as $constant) {
    if (!str_contains($profileNames, 'public const ' . $constant)) {
        $errors[] = 'ObjectTitleAliasProfileName missing profile constant: ' . $constant;
    }
}

$registry = (string) file_get_contents($root . '/src/Service/Title/ObjectTitleAliasProfileRegistry.php');
foreach (['ObjectTitleAliasProfileName::LABEL', 'ObjectTitleAliasProfileName::DISPLAY', 'ALIAS_LABEL', 'ALIAS_DISPLAY_NAME'] as $needle) {
    if (!str_contains($registry, $needle)) {
        $errors[] = 'ObjectTitleAliasProfileRegistry missing alias hardening member: ' . $needle;
    }
}

$decision = (string) file_get_contents($root . '/src/ValueObject/ObjectSystemTokenDecision.php');
foreach (['OBJECT_TITLE_ALIAS', 'BACKEND_OWNED', 'DEFERRED', 'TOKEN_PRIORITY', 'TOKEN_VISIBILITY', 'canonicalDecisions', 'titleAliasTokens', 'deferredTokens'] as $needle) {
    if (!str_contains($decision, $needle)) {
        $errors[] = 'ObjectSystemTokenDecision missing token decision member: ' . $needle;
    }
}

$titleManifest = (string) file_get_contents($root . '/resources/title-alias/manifest.yaml');
foreach (['object_title_label', 'object_title_display', 'covered_business_alias_tokens:', 'deferred_systemic_tokens:', '- priority', '- visibility', 'backend_owned_tokens:', '- id'] as $needle) {
    if (!str_contains($titleManifest, $needle)) {
        $errors[] = 'Title alias manifest missing: ' . $needle;
    }
}

$governance = (string) file_get_contents($root . '/resources/title-alias/object-title-alias-governance.example.yaml');
foreach (['forbidden_wave_22_packs:', '- object_id', '- object_name', '- object_description', '- object_priority', '- object_visibility'] as $needle) {
    if (!str_contains($governance, $needle)) {
        $errors[] = 'Title alias governance missing: ' . $needle;
    }
}

$fieldPackManifest = (string) file_get_contents($root . '/resources/field-pack/manifest.yaml');
foreach (['object_id', 'object_name', 'object_description', 'object_priority', 'object_visibility'] as $forbiddenPack) {
    if (preg_match('/^\s*-\s+' . preg_quote($forbiddenPack, '/') . '\s*$/m', $fieldPackManifest) === 1) {
        $errors[] = 'Forbidden field pack was added in wave 22: ' . $forbiddenPack;
    }
}

$composer = json_decode((string) file_get_contents($root . '/composer.json'), true);
if (!is_array($composer) || !array_key_exists('test:title-alias-hardening', $composer['scripts'] ?? [])) {
    $errors[] = 'composer.json is missing script: test:title-alias-hardening';
}

if ($errors !== []) {
    echo "Objecting title-alias hardening check failed:\n";
    foreach ($errors as $error) {
        echo ' - ' . $error . "\n";
    }
    exit(1);
}

echo "Objecting title-alias hardening check passed.\n";
