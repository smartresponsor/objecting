<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Diagnostic\ObjectDiagnosticEventId;
use App\Objecting\Reporter\Diagnostic\ObjectDiagnosticReporter;
use PHPUnit\Framework\TestCase;

final class ObjectDiagnosticReporterTest extends TestCase
{
    public function testItBuildsStableJsonDiagnosticReport(): void
    {
        $report = (new ObjectDiagnosticReporter())->report(
            ObjectDiagnosticEventId::SCHEMA_MISMATCH,
            'Consumer schema does not match the Objecting manifest.',
            ['component' => 'Addressing', 'columns' => ['object_slug']],
        );

        self::assertSame(ObjectDiagnosticEventId::SCHEMA_MISMATCH, $report->eventId());
        self::assertSame(
            '{"event_id":"objecting.schema.mismatch","message":"Consumer schema does not match the Objecting manifest.","context":{"component":"Addressing","columns":["object_slug"]}}',
            $report->toJson(),
        );
    }

    public function testItRejectsUnknownEventIdentifier(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        (new ObjectDiagnosticReporter())->report('objecting.unknown', 'Unknown event.');
    }
}
