<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Service\Release\ObjectRcMarkerManifestReporter;
use App\Objecting\ValueObject\ObjectPackageSurface;
use App\Objecting\ValueObject\ObjectRcMarkerManifest;
use App\Objecting\ValueObject\ObjectRcMarkerReport;
use PHPUnit\Framework\TestCase;

final class ObjectRcMarkerManifestReporterTest extends TestCase
{
    public function testItMarksCanonicalRcReady(): void
    {
        $manifest = new ObjectRcMarkerManifest(
            rcName: 'objecting_rc1',
            rcCandidate: 'objecting_wave20_platform_constraints',
            packageName: ObjectPackageSurface::COMPOSER_PACKAGE,
            namespacePrefix: ObjectPackageSurface::NAMESPACE_PREFIX,
            bundleClass: ObjectPackageSurface::BUNDLE_CLASS,
            cumulativeArchive: 'objecting_wave20_platform_constraints_cumulative.zip',
            touchedArchive: 'objecting_wave20_platform_constraints_touched.zip',
            applyScript: 'apply_objecting_wave20_platform_constraints_touched.ps1',
            rcStabilizationPath: ObjectPackageSurface::RC_STABILIZATION_EXAMPLE,
            releaseClosurePath: ObjectPackageSurface::RELEASE_CLOSURE_EXAMPLE,
            releaseReadinessPath: ObjectPackageSurface::RELEASE_MANIFEST_EXAMPLE,
            backendImportPath: ObjectPackageSurface::BACKEND_IMPORT_EXAMPLE,
            adoptionPacketPath: ObjectPackageSurface::BACKEND_ADOPTION_PACKET_EXAMPLE,
            exposingBridgePath: ObjectPackageSurface::EXPOSING_BRIDGE_EXAMPLE,
            schemaMirrorPath: ObjectPackageSurface::SCHEMA_MIRROR_EXAMPLE,
            doctrineMappingPath: ObjectPackageSurface::DOCTRINE_MAPPING_EXAMPLE,
            qualityGates: ['composer dump-autoload', 'composer test:quality', 'composer test:platform-constraints', 'composer test:rc', 'php tools/test/objecting_rc1_check.php', 'php tools/test/objecting_platform_constraint_check.php'],
            requiredComposerScripts: ['test:quality', 'test:rc', 'test:rc1', 'test:rc-stabilization', 'test:release-closure', 'test:backend-import', 'test:platform-constraints'],
            finalEntrypoints: [
                ObjectPackageSurface::RC_MARKER_CHECK,
                ObjectPackageSurface::RC_STABILIZATION_CHECK,
                ObjectPackageSurface::RELEASE_CLOSURE_CHECK,
                ObjectPackageSurface::BACKEND_IMPORT_CHECK,
                ObjectPackageSurface::EXPOSING_BRIDGE_CHECK,
                ObjectPackageSurface::SCHEMA_MIRROR_CHECK,
                ObjectPackageSurface::DOCTRINE_MAPPING_CHECK,
                ObjectPackageSurface::PLATFORM_CONSTRAINTS_CHECK,
            ],
        );

        $report = (new ObjectRcMarkerManifestReporter())->report($manifest);

        self::assertSame(ObjectRcMarkerReport::STATUS_READY, $report->status());
        self::assertTrue($report->isReady());
        self::assertSame([], $report->blockingReasons());
        self::assertContains('objecting_rc_quality_gates', $report->checks());
        self::assertContains('rc_accepted', $report->checks());
    }

    public function testItBlocksIncompleteRcMarker(): void
    {
        $manifest = new ObjectRcMarkerManifest(
            rcName: 'objecting_rc1',
            rcCandidate: 'objecting_wave20_platform_constraints',
            packageName: 'wrong/package',
            namespacePrefix: 'App\\Wrong\\',
            bundleClass: 'App\\Wrong\\ObjectBundle',
            cumulativeArchive: 'objecting_wave20_platform_constraints_cumulative.zip',
            touchedArchive: 'objecting_wave20_platform_constraints_touched.zip',
            applyScript: 'apply_objecting_wave20_platform_constraints_touched.ps1',
            rcStabilizationPath: ObjectPackageSurface::RC_STABILIZATION_EXAMPLE,
            releaseClosurePath: ObjectPackageSurface::RELEASE_CLOSURE_EXAMPLE,
            releaseReadinessPath: ObjectPackageSurface::RELEASE_MANIFEST_EXAMPLE,
            backendImportPath: ObjectPackageSurface::BACKEND_IMPORT_EXAMPLE,
            adoptionPacketPath: ObjectPackageSurface::BACKEND_ADOPTION_PACKET_EXAMPLE,
            exposingBridgePath: ObjectPackageSurface::EXPOSING_BRIDGE_EXAMPLE,
            schemaMirrorPath: ObjectPackageSurface::SCHEMA_MIRROR_EXAMPLE,
            doctrineMappingPath: ObjectPackageSurface::DOCTRINE_MAPPING_EXAMPLE,
            qualityGates: ['composer dump-autoload'],
            requiredComposerScripts: ['test:quality'],
            finalEntrypoints: [ObjectPackageSurface::RC_MARKER_CHECK],
            fieldPackFoundationOnly: false,
            objectTitleCanonical: false,
            legacyFree: false,
            backendRuntimeOwner: false,
            exposingSeparated: false,
            rcAccepted: false,
        );

        $report = (new ObjectRcMarkerManifestReporter())->report($manifest);

        self::assertSame(ObjectRcMarkerReport::STATUS_BLOCKED, $report->status());
        self::assertFalse($report->isReady());
        self::assertNotSame([], $report->blockingReasons());
    }
}
