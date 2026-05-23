<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$traitDir = $root . '/src/EntityTrait/Embeddable';
$errors = [];

if (!is_dir($traitDir)) {
    echo "Objecting embeddable initialization check failed:
 - Missing trait directory.
";
    exit(1);
}

$traitFiles = glob($traitDir . '/Object*EmbeddableTrait.php') ?: [];
if ($traitFiles === []) {
    $errors[] = 'No Object*EmbeddableTrait files found.';
}

foreach ($traitFiles as $traitFile) {
    $relative = str_replace($root . '/', '', $traitFile);
    $content = (string) file_get_contents($traitFile);

    if (!preg_match('/private\s+(Object[A-Za-z0-9]+Embeddable)\s+\$(object[A-Za-z0-9]+);/', $content, $match)) {
        $errors[] = 'Trait is missing typed Object* embeddable property: ' . $relative;
        continue;
    }

    $embeddableClass = $match[1];
    $property = $match[2];
    $helper = $property . 'Embeddable';

    if (!str_contains($content, 'protected function initialize' . str_replace('Object', 'Object', substr($embeddableClass, 0, -10)))) {
        $errors[] = 'Trait is missing explicit initializeObject* method: ' . $relative;
    }

    if (!str_contains($content, 'private function ' . $helper . '(): ' . $embeddableClass)) {
        $errors[] = 'Trait is missing lazy private helper ' . $helper . '(): ' . $relative;
    }

    if (!str_contains($content, 'if (!isset($this->' . $property . '))')) {
        $errors[] = 'Trait lazy helper does not guard uninitialized property with isset(): ' . $relative;
    }

    if (!str_contains($content, '$this->' . $property . ' = new ' . $embeddableClass . '(')) {
        $errors[] = 'Trait lazy helper does not create default embeddable: ' . $relative;
    }

    if (preg_match('/\$this->' . preg_quote($property, '/') . '->/', $content) === 1) {
        $errors[] = 'Trait public methods must use lazy helper instead of direct property dereference: ' . $relative;
    }
}

$composer = $root . '/composer.json';
if (is_file($composer)) {
    $json = json_decode((string) file_get_contents($composer), true);
    if (!is_array($json) || !array_key_exists('test:embeddable-initialization', $json['scripts'] ?? [])) {
        $errors[] = 'composer.json is missing test:embeddable-initialization script.';
    }
}

$doc = $root . '/docs/integration/objecting-embeddable-initialization.md';
if (!is_file($doc)) {
    $errors[] = 'Missing embeddable initialization integration doc.';
}

if ($errors !== []) {
    echo "Objecting embeddable initialization check failed:
";
    foreach ($errors as $error) {
        echo ' - ' . $error . "
";
    }
    exit(1);
}

echo "Objecting embeddable initialization check passed.
";
