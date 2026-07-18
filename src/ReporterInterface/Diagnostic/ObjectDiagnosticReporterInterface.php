<?php

declare(strict_types=1);

namespace App\Objecting\ReporterInterface\Diagnostic;

use App\Objecting\Report\ObjectDiagnosticReport;

interface ObjectDiagnosticReporterInterface
{
    /**
     * @param array<string, scalar|array<array-key, scalar|null>|null> $context
     */
    public function report(string $eventId, string $message, array $context = []): ObjectDiagnosticReport;
}
