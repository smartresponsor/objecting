<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$src = $root . '/src';
$errors = [];

/** @var array<string, list<string>> $symbolsByShortName */
$symbolsByShortName = [];
/** @var array<string, array{namespace: string, symbol: string}> $fileDeclarations */
$fileDeclarations = [];

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($src, FilesystemIterator::SKIP_DOTS));
foreach ($iterator as $file) {
    if (!$file instanceof SplFileInfo || 'php' !== $file->getExtension()) {
        continue;
    }

    $path = $file->getPathname();
    $contents = (string) file_get_contents($path);
    if (1 !== preg_match('/^namespace\s+([^;]+);/m', $contents, $namespaceMatch)
        || 1 !== preg_match('/\b(?:final\s+|readonly\s+|abstract\s+)*(?:class|interface|trait|enum)\s+([A-Za-z_][A-Za-z0-9_]*)/', $contents, $symbolMatch)) {
        continue;
    }

    $namespace = $namespaceMatch[1];
    $symbol = $symbolMatch[1];
    $fqcn = $namespace . '\\' . $symbol;
    $relative = str_replace('\\', '/', substr($path, strlen($root) + 1));

    $symbolsByShortName[$symbol][] = $fqcn;
    $fileDeclarations[$relative] = ['namespace' => $namespace, 'symbol' => $symbol];
}

foreach ($fileDeclarations as $relative => $declaration) {
    $contents = (string) file_get_contents($root . '/' . $relative);
    $imports = [];

    if (preg_match_all('/^use\s+(App\\\\Objecting\\\\[A-Za-z0-9_\\\\]+)(?:\s+as\s+([A-Za-z_][A-Za-z0-9_]*))?;/m', $contents, $useMatches, PREG_SET_ORDER)) {
        foreach ($useMatches as $useMatch) {
            $fqcn = $useMatch[1];
            $alias = $useMatch[2] ?? substr($fqcn, strrpos($fqcn, '\\') + 1);
            $imports[$alias] = $fqcn;
        }
    }

    foreach (token_get_all($contents) as $token) {
        if (!is_array($token) || T_STRING !== $token[0]) {
            continue;
        }

        $shortName = $token[1];
        if (1 !== preg_match('/^Object[A-Z][A-Za-z0-9_]*$/', $shortName)
            || $shortName === $declaration['symbol']
            || isset($imports[$shortName])) {
            continue;
        }

        $currentNamespaceFqcn = $declaration['namespace'] . '\\' . $shortName;
        if (in_array($currentNamespaceFqcn, $symbolsByShortName[$shortName] ?? [], true)) {
            continue;
        }

        $candidates = $symbolsByShortName[$shortName] ?? [];
        if ([] === $candidates) {
            continue;
        }

        $errors[] = sprintf(
            '%s references internal type %s without importing one of: %s',
            $relative,
            $shortName,
            implode(', ', $candidates),
        );
    }
}

if ([] !== $errors) {
    fwrite(STDERR, "Objecting internal type resolution check failed:\n - " . implode("\n - ", array_values(array_unique($errors))) . "\n");
    exit(1);
}

echo "Objecting internal type resolution check passed.\n";
