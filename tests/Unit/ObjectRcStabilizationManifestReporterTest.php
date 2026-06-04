<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Manifest\ObjectRcStabilizationManifest;
use App\Objecting\Report\ObjectRcStabilizationReport;
use App\Objecting\Reporter\Release\ObjectRcStabilizationManifestReporter;
use App\Objecting\Surface\ObjectPackageSurface;
use PHPUnit\Framework\TestCase;

final class ObjectRcStabilizationManifestReporterTest extends TestCase
{
    public function testItMarksCanonicalRcStabilizationReady(): void
    {
        $manifest = new ObjectRcStabilizationManifest(
            stabilizationCandidate: 'objecting_wave18_rc_stabilization',
            packageName: ObjectPackageSurface::COMPOSER_PACKAGE,
            namespacePrefix: ObjectPackageSurface::NAMESPACE_PREFIX,
            bundleClass: ObjectPackageSurface::BUNDLE_CLASS,
            releaseClosurePath: ObjectPackageSurface::RELEASE_CLOSURE_EXAMPLE,
            releaseReadinessPath: ObjectPackageSurface::RELEASE_MANIFEST_EXAMPLE,
            backendImportPath: ObjectPackageSurface::BACKEND_IMPORT_EXAMPLE,
            adoptionPacketPath: ObjectPackageSurface::BACKEND_ADOPTION_PACKET_EXAMPLE,
            exposingBridgePath: ObjectPackageSurface::EXPOSING_BRIDGE_EXAMPLE,
            schemaMirrorPath: ObjectPackageSurface::SCHEMA_MIRROR_EXAMPLE,
            doctrineMappingPath: ObjectPackageSurface::DOCTRINE_MAPPING_EXAMPLE,
            qualityGates: ['composer dump-autoload', 'composer test:quality', 'php tools/test/objecting_rc_stabilization_check.php'],
            requiredComposerScripts: ['test:quality', 'test:rc-stabilization', 'test:release-closure', 'test:backend-import'],
            finalEntrypoints: [
                ObjectPackageSurface::RELEASE_CLOSURE_CHECK,
                ObjectPackageSurface::BACKEND_IMPORT_CHECK,
                ObjectPackageSurface::EXPOSING_BRIDGE_CHECK,
                ObjectPackageSurface::SCHEMA_MIRROR_CHECK,
                ObjectPackageSurface::DOCTRINE_MAPPING_CHECK,
                ObjectPackageSurface::RC_STABILIZATION_CHECK,
            ],
        );

        $report = (new ObjectRcStabilizationManifestReporter())->report($manifest);

        self::assertSame(ObjectRcStabilizationReport::STATUS_READY, $report->status());
        self::assertTrue($report->isReady());
        self::assertSame([], $report->blockingReasons());
        self::assertContains('rc_stabilization_contract_paths', $report->checks());
        self::assertContains('rc_marker_pending', $report->checks());
    }

    public function testItBlocksIncompleteRcStabilization(): void
    {
        $manifest = new ObjectRcStabilizationManifest(
            stabilizationCandidate: 'objecting_wave18_rc_stabilization',
            packageName: 'wrong/package',
            namespacePrefix: 'App\\Wrong\\',
            bundleClass: 'App\\Wrong\\ObjectBundle',
            releaseClosurePath: ObjectPackageSurface::RELEASE_CLOSURE_EXAMPLE,
            releaseReadinessPath: ObjectPackageSurface::RELEASE_MANIFEST_EXAMPLE,
            backendImportPath: ObjectPackageSurface::BACKEND_IMPORT_EXAMPLE,
            adoptionPacketPath: ObjectPackageSurface::BACKEND_ADOPTION_PACKET_EXAMPLE,
            exposingBridgePath: ObjectPackageSurface::EXPOSING_BRIDGE_EXAMPLE,
            schemaMirrorPath: ObjectPackageSurface::SCHEMA_MIRROR_EXAMPLE,
            doctrineMappingPath: ObjectPackageSurface::DOCTRINE_MAPPING_EXAMPLE,
            qualityGates: ['composer dump-autoload'],
            requiredComposerScripts: ['test:quality'],
            finalEntrypoints: [ObjectPackageSurface::RELEASE_CLOSURE_CHECK],
            fieldPackFoundationOnly: false,
            objectTitleCanonical: false,
            legacyFree: false,
            backendRuntimeOwner: false,
            exposingSeparated: false,
            rcMarkerPending: false,
        );

        $report = (new ObjectRcStabilizationManifestReporter())->report($manifest);

        self::assertSame(ObjectRcStabilizationReport::STATUS_BLOCKED, $report->status());
        self::assertFalse($report->isReady());
        self::assertNotSame([], $report->blockingReasons());
    }
}
