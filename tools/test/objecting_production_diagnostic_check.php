<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];

$requiredFiles = [
    'src/Diagnostic/ObjectDiagnosticEventId.php',
    'src/Report/ObjectDiagnosticReport.php',
    'src/Reporter/Diagnostic/ObjectDiagnosticReporter.php',
    'src/ReporterInterface/Diagnostic/ObjectDiagnosticReporterInterface.php',
    'tests/Unit/ObjectDiagnosticReporterTest.php',
    'docs/integration/objecting-production-diagnostics.md',
];

foreach ($requiredFiles as $requiredFile) {
    if (!is_file($root.'/'.$requiredFile)) {
        $errors[] = 'Missing Objecting production diagnostic file: '.$requiredFile;
    }
}

$eventFile = file_get_contents($root.'/src/Diagnostic/ObjectDiagnosticEventId.php') ?: '';
foreach ([
    'objecting.manifest.invalid',
    'objecting.profile.unknown',
    'objecting.field_pack.unknown',
    'objecting.lifecycle.invariant_violation',
    'objecting.schema.mismatch',
    'objecting.title_alias.conflict',
    'objecting.consumer.duplicate_local_system_field',
    'objecting.public_api.deprecated',
] as $eventId) {
    if (!str_contains($eventFile, $eventId)) {
        $errors[] = 'Missing stable diagnostic event identifier: '.$eventId;
    }
}

$files = array_merge(
    glob($root.'/src/Embeddable/*.php') ?: [],
    glob($root.'/src/EntityTrait/Embeddable/*.php') ?: [],
);
foreach ($files as $file) {
    $contents = file_get_contents($file) ?: '';
    if (str_contains($contents, 'LoggerInterface') || str_contains($contents, 'Psr\\Log')) {
        $errors[] = 'Embeddables and embeddable traits must remain logger-free: '.str_replace($root.'/', '', $file);
    }
}

$composer = json_decode(file_get_contents($root.'/composer.json') ?: '', true);
if (!is_array($composer)) {
    $errors[] = 'composer.json is not valid JSON.';
} else {
    if (!array_key_exists('test:production-diagnostics', $composer['scripts'] ?? [])) {
        $errors[] = 'composer.json is missing test:production-diagnostics.';
    }
    $quality = $composer['scripts']['test:quality'] ?? [];
    $qualityText = is_array($quality) ? implode("\n", $quality) : (string) $quality;
    if (!str_contains($qualityText, '@test:production-diagnostics')) {
        $errors[] = 'composer test:quality must include @test:production-diagnostics.';
    }
}

$services = file_get_contents($root.'/config/services.yaml') ?: '';
if (!str_contains($services, 'App\\Objecting\\ReporterInterface\\Diagnostic\\ObjectDiagnosticReporterInterface:')) {
    $errors[] = 'config/services.yaml is missing ObjectDiagnosticReporterInterface alias.';
}

if ([] !== $errors) {
    echo "Objecting production diagnostic check failed:\n";
    foreach ($errors as $error) {
        echo ' - '.$error."\n";
    }
    exit(1);
}

