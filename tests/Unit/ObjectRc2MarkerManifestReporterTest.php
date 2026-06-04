<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Manifest\ObjectRc2MarkerManifest;
use App\Objecting\Report\ObjectRc2MarkerReport;
use App\Objecting\Reporter\Release\ObjectRc2MarkerManifestReporter;
use App\Objecting\Surface\ObjectPackageSurface;
use App\Objecting\ValueObject\ObjectFieldPackName;
use PHPUnit\Framework\TestCase;

final class ObjectRc2MarkerManifestReporterTest extends TestCase
{
    public function testItMarksCanonicalRc2Ready(): void
    {
        $manifest = new ObjectRc2MarkerManifest(
            rcName: 'objecting_rc2',
            rcCandidate: 'objecting_wave25_rc2_marker',
            previousRcName: 'objecting_rc1',
            packageName: ObjectPackageSurface::COMPOSER_PACKAGE,
            namespacePrefix: ObjectPackageSurface::NAMESPACE_PREFIX,
            bundleClass: ObjectPackageSurface::BUNDLE_CLASS,
            cumulativeArchive: 'objecting_wave25_rc2_marker_cumulative.zip',
            touchedArchive: 'objecting_wave25_rc2_marker_touched.zip',
            applyScript: 'apply_objecting_wave25_rc2_marker_touched.ps1',
            releaseClosurePath: ObjectPackageSurface::RELEASE_CLOSURE_EXAMPLE,
            fieldPackManifestPath: ObjectPackageSurface::FIELD_PACK_MANIFEST,
            titleAliasManifestPath: ObjectPackageSurface::TITLE_ALIAS_MANIFEST,
            backendMigrationCommandPath: ObjectPackageSurface::BACKEND_MIGRATION_COMMAND_EXAMPLE,
            backendCloneCleanupPath: ObjectPackageSurface::BACKEND_CLONE_CLEANUP_EXAMPLE,
            platformConstraintsPath: ObjectPackageSurface::PLATFORM_CONSTRAINTS_EXAMPLE,
            qualityGates: ['composer dump-autoload', 'composer test:quality', 'composer test:rc2', 'php tools/test/objecting_rc2_check.php'],
            requiredComposerScripts: ['test:quality', 'test:rc', 'test:rc2', 'test:systemic-field-packs', 'test:title-alias-hardening', 'test:backend-migration-command', 'test:backend-clone-cleanup'],
            finalEntrypoints: [
                ObjectPackageSurface::RC2_MARKER_CHECK,
                ObjectPackageSurface::SYSTEMIC_FIELD_PACKS_CHECK,
                ObjectPackageSurface::TITLE_ALIAS_HARDENING_CHECK,
                ObjectPackageSurface::BACKEND_MIGRATION_COMMAND_CHECK,
                ObjectPackageSurface::BACKEND_CLONE_CLEANUP_CHECK,
                ObjectPackageSurface::PLATFORM_CONSTRAINTS_CHECK,
            ],
            includedFieldPacks: [
                ObjectFieldPackName::IDENTITY,
                ObjectFieldPackName::AUDIT,
                ObjectFieldPackName::TITLE,
                ObjectFieldPackName::STATE,
                ObjectFieldPackName::SOURCE,
                ObjectFieldPackName::FINGERPRINT,
                ObjectFieldPackName::SCOPE,
            ],
            forbiddenFieldPacks: ['object_id', 'object_name', 'object_description', 'object_priority', 'object_visibility'],
            deferredTokens: ['priority', 'visibility'],
        );

        $report = (new ObjectRc2MarkerManifestReporter())->report($manifest);

        self::assertSame(ObjectRc2MarkerReport::STATUS_READY, $report->status());
        self::assertTrue($report->isReady());
        self::assertSame([], $report->blockingReasons());
        self::assertContains('objecting_rc2_included_field_packs', $report->checks());
        self::assertContains('objecting_rc2_forbidden_field_packs', $report->checks());
    }

    public function testItBlocksUnsafeRc2Marker(): void
    {
        $manifest = new ObjectRc2MarkerManifest(
            rcName: 'objecting_rc2',
            rcCandidate: 'objecting_wave25_rc2_marker',
            previousRcName: 'objecting_rc1',
            packageName: 'wrong/package',
            namespacePrefix: ObjectPackageSurface::NAMESPACE_PREFIX,
            bundleClass: ObjectPackageSurface::BUNDLE_CLASS,
            cumulativeArchive: 'objecting_wave25_rc2_marker_cumulative.zip',
            touchedArchive: 'objecting_wave25_rc2_marker_touched.zip',
            applyScript: 'apply_objecting_wave25_rc2_marker_touched.ps1',
            releaseClosurePath: ObjectPackageSurface::RELEASE_CLOSURE_EXAMPLE,
            fieldPackManifestPath: ObjectPackageSurface::FIELD_PACK_MANIFEST,
            titleAliasManifestPath: ObjectPackageSurface::TITLE_ALIAS_MANIFEST,
            backendMigrationCommandPath: ObjectPackageSurface::BACKEND_MIGRATION_COMMAND_EXAMPLE,
            backendCloneCleanupPath: ObjectPackageSurface::BACKEND_CLONE_CLEANUP_EXAMPLE,
            platformConstraintsPath: ObjectPackageSurface::PLATFORM_CONSTRAINTS_EXAMPLE,
            qualityGates: ['composer dump-autoload'],
            requiredComposerScripts: ['test:quality'],
            finalEntrypoints: [ObjectPackageSurface::RC2_MARKER_CHECK],
            includedFieldPacks: [ObjectFieldPackName::IDENTITY],
            forbiddenFieldPacks: ['object_id'],
            deferredTokens: ['priority'],
            legacyFree: false,
            exposingSeparated: false,
            rcAccepted: false,
        );

        $report = (new ObjectRc2MarkerManifestReporter())->report($manifest);

        self::assertSame(ObjectRc2MarkerReport::STATUS_BLOCKED, $report->status());
        self::assertFalse($report->isReady());
        self::assertNotSame([], $report->blockingReasons());
    }
}
