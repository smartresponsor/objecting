<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];

$requiredFiles = [
    'src/Manifest/ObjectPlatformConstraintManifest.php',
    'src/Report/ObjectPlatformConstraintReport.php',
    'src/Reporter/Release/ObjectPlatformConstraintReporter.php',
    'src/ReporterInterface/Release/ObjectPlatformConstraintReporterInterface.php',
    'tests/Unit/ObjectPlatformConstraintReporterTest.php',
    'resources/release/objecting-platform-constraints.example.yaml',
    'docs/integration/objecting-platform-constraints.md',
    'tools/test/objecting_platform_constraint_check.php',
];

foreach ($requiredFiles as $requiredFile) {
    if (!is_file($root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $requiredFile))) {
        $errors[] = 'Missing Objecting platform constraint file: ' . $requiredFile;
    }
}

$composerFile = $root . '/composer.json';
if (is_file($composerFile)) {
    $composerText = (string) file_get_contents($composerFile);
    $composer = json_decode($composerText, true);
    if (!is_array($composer)) {
        $errors[] = 'composer.json is not valid JSON.';
    } else {
        $require = $composer['require'] ?? [];
        if (($require['php'] ?? null) !== '^8.4') {
            $errors[] = 'composer.json must require php ^8.4 only.';
        }

        foreach (['symfony/config', 'symfony/dependency-injection', 'symfony/http-kernel', 'symfony/uid', 'symfony/yaml'] as $package) {
            if (($require[$package] ?? null) !== '^8.0') {
                $errors[] = sprintf('composer.json must require %s ^8.0 only.', $package);
            }
        }

        if (($composer['extra']['symfony']['require'] ?? null) !== '^8.0') {
            $errors[] = 'composer.json extra.symfony.require must be ^8.0.';
        }

        foreach (['^7.0 || ^8.0', '^7 || ^8', '^7.0', '7.*'] as $forbidden) {
            if (str_contains($composerText, $forbidden)) {
                $errors[] = 'composer.json contains forbidden Symfony 7 constraint: ' . $forbidden;
            }
        }

        if (!array_key_exists('test:platform-constraints', $composer['scripts'] ?? [])) {
            $errors[] = 'composer.json is missing script: test:platform-constraints.';
        }

        $qualityScript = $composer['scripts']['test:quality'] ?? [];
        $qualityScriptText = is_array($qualityScript) ? implode("\n", $qualityScript) : (string) $qualityScript;
        if (!str_contains($qualityScriptText, '@test:platform-constraints')) {
            $errors[] = 'composer test:quality must include @test:platform-constraints.';
        }
    }
}

$manifestFile = $root . '/resources/release/objecting-platform-constraints.example.yaml';
if (is_file($manifestFile)) {
    $manifest = file_get_contents($manifestFile) ?: '';
    foreach ([
        'object_platform_constraints_version: 1',
        'constraint_candidate: objecting_wave20_platform_constraints',
        "php: '^8.4'",
        "symfony: '^8.0'",
        'symfony/config: \'^8.0\'',
        'symfony/dependency-injection: \'^8.0\'',
        'symfony/http-kernel: \'^8.0\'',
        'symfony/uid: \'^8.0\'',
        'symfony/yaml: \'^8.0\'',
        'require: \'^8.0\'',
        'symfony_7_forbidden: true',
        'mixed_symfony_7_8_forbidden: true',
        'composer test:platform-constraints',
        'php tools/test/objecting_platform_constraint_check.php',
    ] as $marker) {
        if (!str_contains($manifest, $marker)) {
            $errors[] = 'Objecting platform constraints manifest is missing marker: ' . $marker;
        }
    }
}

$servicesFile = $root . '/config/services.yaml';
if (is_file($servicesFile)) {
    $services = file_get_contents($servicesFile) ?: '';
    if (!str_contains($services, 'App\\Objecting\\ReporterInterface\\Release\\ObjectPlatformConstraintReporterInterface:')) {
        $errors[] = 'config/services.yaml is missing ObjectPlatformConstraintReporterInterface alias.';
    }
}

$surfaceFile = $root . '/src/Surface/ObjectPackageSurface.php';
if (is_file($surfaceFile)) {
    $surface = file_get_contents($surfaceFile) ?: '';
    foreach (['PLATFORM_CONSTRAINTS_EXAMPLE', 'PLATFORM_CONSTRAINTS_DOC', 'PLATFORM_CONSTRAINTS_CHECK', 'SYMFONY_REQUIRE_PACKAGES'] as $constant) {
        if (!str_contains($surface, 'public const ' . $constant)) {
            $errors[] = 'ObjectPackageSurface is missing platform constraint constant: ' . $constant;
        }
    }
}

$rc1File = $root . '/resources/release/objecting-rc1.example.yaml';
if (is_file($rc1File)) {
    $rc1 = file_get_contents($rc1File) ?: '';
    foreach (['objecting-platform-constraints.example.yaml', 'composer test:platform-constraints', 'php tools/test/objecting_platform_constraint_check.php'] as $marker) {
        if (!str_contains($rc1, $marker)) {
            $errors[] = 'Objecting RC1 marker is missing platform constraint marker: ' . $marker;
        }
    }
}

$readmeFile = $root . '/README.md';
if (is_file($readmeFile)) {
    $readme = file_get_contents($readmeFile) ?: '';
    foreach (['PHP `^8.4`', 'Symfony `^8.0`', 'composer test:platform-constraints'] as $marker) {
        if (!str_contains($readme, $marker)) {
            $errors[] = 'README is missing platform constraint marker: ' . $marker;
        }
    }
}

if ($errors !== []) {
    echo "Objecting platform constraint check failed:\n";
    foreach ($errors as $error) {
        echo ' - ' . $error . "\n";
    }
    exit(1);
}

echo "Objecting platform constraint check passed.\n";
