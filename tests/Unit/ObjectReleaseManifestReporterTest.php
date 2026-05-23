<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Service\Release\ObjectReleaseManifestReporter;
use App\Objecting\ValueObject\ObjectReleaseManifest;
use PHPUnit\Framework\TestCase;

final class ObjectReleaseManifestReporterTest extends TestCase
{
    public function testItMarksCanonicalReleaseReady(): void
    {
        $manifest = new ObjectReleaseManifest(
            releaseCandidate: 'objecting_wave10_release_readiness',
            packageName: 'smart-responsor/objecting',
            namespacePrefix: 'App\\Objecting\\',
            bundleClass: 'App\\Objecting\\ObjectBundle',
            cumulativeArchive: 'objecting_wave10_release_readiness_cumulative.zip',
            touchedArchive: 'objecting_wave10_release_readiness_touched.zip',
            qualityGates: ['composer dump-autoload', 'composer test:quality'],
            requiredComposerScripts: ['test:canon', 'test:package-surface', 'test:release-readiness', 'test:quality'],
            consumerContracts: [
                'resources/consumer/object-field-packs.example.yaml',
                'resources/consumer/object-backend-migration-readiness.example.yaml',
                'resources/consumer/object-backend-adoption.example.yaml',
                'resources/consumer/object-backend-handoff.example.yaml',
            ],
            fieldPackFoundationOnly: true,
            legacyFree: true,
        );

        $report = (new ObjectReleaseManifestReporter())->report($manifest);

        self::assertTrue($report->isReady());
        self::assertSame('ready', $report->status());
        self::assertSame([], $report->blockingReasons());
        self::assertContains('release_consumer_contracts', $report->checks());
    }

    public function testItBlocksInvalidRelease(): void
    {
        $manifest = new ObjectReleaseManifest(
            releaseCandidate: 'objecting_wave10_release_readiness',
            packageName: 'wrong/package',
            namespacePrefix: 'App\\Wrong\\',
            bundleClass: 'App\\Wrong\\ObjectBundle',
            cumulativeArchive: 'objecting_wave10_release_readiness_cumulative.zip',
            touchedArchive: 'objecting_wave10_release_readiness_touched.zip',
            qualityGates: ['composer dump-autoload'],
            requiredComposerScripts: ['test:canon'],
            consumerContracts: ['resources/consumer/object-field-packs.example.yaml'],
            fieldPackFoundationOnly: false,
            legacyFree: false,
        );

        $report = (new ObjectReleaseManifestReporter())->report($manifest);

        self::assertFalse($report->isReady());
        self::assertSame('blocked', $report->status());
        self::assertNotSame([], $report->blockingReasons());
    }
}
