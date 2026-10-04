<?php

declare(strict_types=1);

namespace App\Objecting\Reporter\Diagnostic;

use App\Objecting\Diagnostic\ObjectDiagnosticEventId;
use App\Objecting\Report\ObjectDiagnosticReport;
use App\Objecting\ReporterInterface\Diagnostic\ObjectDiagnosticReporterInterface;

final readonly class ObjectDiagnosticReporter implements ObjectDiagnosticReporterInterface
{
    public function report(string $eventId, string $message, array $context = []): ObjectDiagnosticReport
    {
        if (!ObjectDiagnosticEventId::isKnown($eventId)) {
            throw new \InvalidArgumentException(sprintf('Unknown Objecting diagnostic event identifier "%s".', $eventId));
        }

        $message = trim($message);
        if ('' === $message) {
            throw new \InvalidArgumentException('Objecting diagnostic message must not be empty.');
        }

        return new ObjectDiagnosticReport($eventId, $message, $context);
    }
}
