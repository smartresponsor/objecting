<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];

$scanRoots = [
    $root . '/src',
    $root . '/config',
    $root . '/resources/field-pack',
    $root . '/resources/consumer',
    $root . '/tests',
];

$forbiddenPatterns = [
    '/tenant_id/i' => 'tenant_id',
    '/tenantId/' => 'tenantId',
    '/object_tenant/i' => 'object_tenant',
    '/ObjectScope(?:Embeddable|EmbeddableTrait|dInterface)/' => 'ObjectScope surface',
    '/object_updated_(?:at|by)/' => 'updated lifecycle columns',
    '/getObjectUpdated(?:At|By)/' => 'updated lifecycle methods',
];

foreach ($scanRoots as $scanRoot) {
    if (!is_dir($scanRoot)) {
        continue;
    }

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($scanRoot));
    foreach ($iterator as $file) {
        if (!$file->isFile() || !in_array($file->getExtension(), ['php', 'yaml', 'yml', 'json'], true)) {
            continue;
        }

        $contents = file_get_contents($file->getPathname());
        if (false === $contents) {
            $errors[] = 'Cannot read ' . $file->getPathname();
            continue;
        }

        foreach ($forbiddenPatterns as $pattern => $label) {
            if (1 === preg_match($pattern, $contents)) {
                $errors[] = sprintf('%s contains forbidden %s drift.', str_replace($root . '/', '', $file->getPathname()), $label);
            }
        }
    }
}

$identityTrait = (string) file_get_contents($root . '/src/EntityTrait/Embeddable/ObjectIdentityEmbeddableTrait.php');
foreach (["#[ORM\\Id]", "#[ORM\\GeneratedValue]", 'private ?int $id = null;', "public function getId(): ?int"] as $marker) {
    if (str_contains($identityTrait, $marker)) {
        $errors[] = 'ObjectIdentityEmbeddableTrait must not own consumer Doctrine primary-key marker: ' . $marker;
    }
}

$identityManifest = (string) file_get_contents($root . '/resources/field-pack/object-identity.yaml');
if (preg_match('/^\s*-\s+id\s*$/m', $identityManifest)) {
    $errors[] = 'object_identity field pack must not own the consumer Doctrine primary-key column id.';
}
if (!str_contains($identityManifest, 'consumer_primary_key_remains_authoritative')) {
    $errors[] = 'object_identity field pack must preserve the consumer-owned primary-key invariant.';
}

$requiredFiles = [
    'src/Embeddable/ObjectAuditEmbeddable.php',
    'src/Embeddable/ObjectSoftDeleteEmbeddable.php',
    'docs/architecture/objecting-vendor-identity-canon.md',
];

foreach ($requiredFiles as $requiredFile) {
    if (!is_file($root . '/' . $requiredFile)) {
        $errors[] = 'Missing canonical lifecycle identity artifact: ' . $requiredFile;
    }
}

if ([] !== $errors) {
    fwrite(STDERR, "Objecting lifecycle identity canon check failed:
- " . implode("
- ", array_values(array_unique($errors))) . "
");
    exit(1);
}

echo "Objecting lifecycle identity canon check passed.
";
