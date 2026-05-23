<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Service\Release\ObjectReleaseClosureManifestReporter;
use App\Objecting\ValueObject\ObjectPackageSurface;
use App\Objecting\ValueObject\ObjectReleaseClosureManifest;
use App\Objecting\ValueObject\ObjectReleaseClosureReport;
use PHPUnit\Framework\TestCase;

final class ObjectReleaseClosureManifestReporterTest extends TestCase
{
    public function testItMarksCanonicalReleaseClosureReady(): void
    {
        $manifest = new ObjectReleaseClosureManifest(
            closureCandidate: 'objecting_wave18_rc_stabilization',
            packageName: ObjectPackageSurface::COMPOSER_PACKAGE,
            namespacePrefix: ObjectPackageSurface::NAMESPACE_PREFIX,
            bundleClass: ObjectPackageSurface::BUNDLE_CLASS,
            cumulativeArchive: 'objecting_wave18_rc_stabilization_cumulative.zip',
            touchedArchive: 'objecting_wave18_rc_stabilization_touched.zip',
            qualityGates: ['composer dump-autoload', 'composer test:quality', 'composer test:rc-stabilization'],
            releaseArtifacts: [
                ObjectPackageSurface::RELEASE_MANIFEST_EXAMPLE,
                ObjectPackageSurface::RELEASE_CLOSURE_EXAMPLE,
                ObjectPackageSurface::RC_STABILIZATION_EXAMPLE,
                ObjectPackageSurface::FIELD_PACK_MANIFEST,
                ObjectPackageSurface::TITLE_ALIAS_MANIFEST,
            ],
            consumerContracts: [
                ObjectPackageSurface::CONSUMER_EXAMPLE,
                ObjectPackageSurface::BACKEND_MIGRATION_READINESS_EXAMPLE,
                ObjectPackageSurface::BACKEND_ADOPTION_EXAMPLE,
                ObjectPackageSurface::BACKEND_HANDOFF_EXAMPLE,
                ObjectPackageSurface::BACKEND_ADOPTION_PACKET_EXAMPLE,
                ObjectPackageSurface::EXPOSING_BRIDGE_EXAMPLE,
            ],
            nextTracks: ['backend_component_migration', 'exposing_api_contract'],
        );

        $report = (new ObjectReleaseClosureManifestReporter())->report($manifest);

        self::assertSame(ObjectReleaseClosureReport::STATUS_READY, $report->status());
        self::assertTrue($report->isReady());
        self::assertSame([], $report->blockingReasons());
        self::assertContains('object_title_canonical', $report->checks());
        self::assertContains('exposing_separated', $report->checks());
    }

    public function testItBlocksIncompleteReleaseClosure(): void
    {
        $manifest = new ObjectReleaseClosureManifest(
            closureCandidate: 'objecting_wave18_rc_stabilization',
            packageName: 'wrong/package',
            namespacePrefix: 'App\\Wrong\\',
            bundleClass: 'App\\Wrong\\ObjectBundle',
            cumulativeArchive: 'objecting_wave18_rc_stabilization_cumulative.zip',
            touchedArchive: 'objecting_wave18_rc_stabilization_touched.zip',
            qualityGates: ['composer dump-autoload'],
            releaseArtifacts: [ObjectPackageSurface::RELEASE_MANIFEST_EXAMPLE],
            consumerContracts: [ObjectPackageSurface::CONSUMER_EXAMPLE],
            nextTracks: ['backend_component_migration'],
            fieldPackFoundationOnly: false,
            objectTitleCanonical: false,
            legacyFree: false,
            backendRuntimeOwner: false,
            exposingSeparated: false,
        );

        $report = (new ObjectReleaseClosureManifestReporter())->report($manifest);

        self::assertSame(ObjectReleaseClosureReport::STATUS_BLOCKED, $report->status());
        self::assertFalse($report->isReady());
        self::assertNotSame([], $report->blockingReasons());
    }
}
