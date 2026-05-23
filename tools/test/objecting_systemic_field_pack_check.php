<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];
$packs = [
    'object_state' => [
        'file' => 'object-state.yaml',
        'embeddable' => 'src/Embeddable/ObjectStateEmbeddable.php',
        'trait' => 'src/EntityTrait/Embeddable/ObjectStateEmbeddableTrait.php',
        'interface' => 'src/EntityInterface/ObjectStatefulInterface.php',
        'columns' => ['object_active', 'object_enabled', 'object_status'],
    ],
    'object_source' => [
        'file' => 'object-source.yaml',
        'embeddable' => 'src/Embeddable/ObjectSourceEmbeddable.php',
        'trait' => 'src/EntityTrait/Embeddable/ObjectSourceEmbeddableTrait.php',
        'interface' => 'src/EntityInterface/ObjectSourcedInterface.php',
        'columns' => ['object_source', 'object_provider', 'object_external_id', 'object_source_type'],
    ],
    'object_fingerprint' => [
        'file' => 'object-fingerprint.yaml',
        'embeddable' => 'src/Embeddable/ObjectFingerprintEmbeddable.php',
        'trait' => 'src/EntityTrait/Embeddable/ObjectFingerprintEmbeddableTrait.php',
        'interface' => 'src/EntityInterface/ObjectFingerprintedInterface.php',
        'columns' => ['object_hash', 'object_checksum', 'object_algorithm'],
    ],
    'object_scope' => [
        'file' => 'object-scope.yaml',
        'embeddable' => 'src/Embeddable/ObjectScopeEmbeddable.php',
        'trait' => 'src/EntityTrait/Embeddable/ObjectScopeEmbeddableTrait.php',
        'interface' => 'src/EntityInterface/ObjectScopedInterface.php',
        'columns' => ['object_scope', 'object_tenant', 'object_organization', 'object_owner'],
    ],
];
$manifest = (string) file_get_contents($root . '/resources/field-pack/manifest.yaml');
$nameFile = (string) file_get_contents($root . '/src/ValueObject/ObjectFieldPackName.php');
$registry = (string) file_get_contents($root . '/src/Service/FieldPack/ObjectFieldPackRegistry.php');
foreach ($packs as $pack => $spec) {
    if (!str_contains($manifest, '- ' . $pack)) {
        $errors[] = 'Field-pack manifest is missing ' . $pack;
    }
    foreach (['embeddable', 'trait', 'interface'] as $kind) {
        if (!is_file($root . '/' . $spec[$kind])) {
            $errors[] = sprintf('%s is missing %s file: %s', $pack, $kind, $spec[$kind]);
        }
    }
    $yamlFile = $root . '/resources/field-pack/' . $spec['file'];
    if (!is_file($yamlFile)) {
        $errors[] = 'Missing field-pack YAML: ' . $spec['file'];
        continue;
    }
    $yaml = (string) file_get_contents($yamlFile);
    if (!str_contains($yaml, 'name: ' . $pack)) {
        $errors[] = 'Field-pack YAML name mismatch for ' . $pack;
    }
    foreach ($spec['columns'] as $column) {
        if (!str_contains($yaml, '- ' . $column)) {
            $errors[] = sprintf('%s YAML missing column %s', $pack, $column);
        }
        if (!str_contains($registry, "'" . $column . "'")) {
            $errors[] = sprintf('Registry missing column %s for %s', $column, $pack);
        }
    }
    $constantName = strtoupper(str_replace('object_', '', $pack));
    if (!str_contains($nameFile, 'public const ' . $constantName . " = '" . $pack . "';")) {
        $errors[] = 'ObjectFieldPackName missing constant for ' . $pack;
    }
}
foreach (['object_id', 'object_name', 'object_description'] as $forbiddenPack) {
    if (preg_match('/^\s*-\s+' . preg_quote($forbiddenPack, '/') . '\s*$/m', $manifest) === 1) {
        $errors[] = 'Objecting must not add forbidden pack in wave 21: ' . $forbiddenPack;
    }
}
if ($errors !== []) {
    echo "Objecting systemic field-pack check failed:\n";
    foreach ($errors as $error) {
        echo ' - ' . $error . "\n";
    }
    exit(1);
}
echo "Objecting systemic field-pack check passed.\n";
