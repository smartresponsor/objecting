<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Manifest\ObjectSiblingPilotMigrationHandoffManifest;
use App\Objecting\Report\ObjectSiblingPilotMigrationHandoffReport;
use App\Objecting\Reporter\FieldPack\ObjectSiblingPilotMigrationHandoffReporter;
use App\Objecting\Surface\ObjectPackageSurface;
use App\Objecting\ValueObject\ObjectFieldPackName;
use PHPUnit\Framework\TestCase;

final class ObjectSiblingPilotMigrationHandoffReporterTest extends TestCase
{
    public function testItMarksCanonicalSiblingPilotMigrationHandoffReady(): void
    {
        $manifest = new ObjectSiblingPilotMigrationHandoffManifest(
            name: 'objecting_sibling_pilot_migration_handoff',
            packageName: ObjectPackageSurface::COMPOSER_PACKAGE,
            objectingBaseline: 'objecting_rc2',
            sourceAudit: 'workspace-objecting-field-pack-audit.md',
            pilotComponents: ['Addressing', 'Taxating'],
            lockedObjectingArtifacts: [
                ObjectPackageSurface::RC2_MARKER_EXAMPLE,
                ObjectPackageSurface::BACKEND_MIGRATION_COMMAND_EXAMPLE,
                ObjectPackageSurface::BACKEND_CLONE_CLEANUP_EXAMPLE,
                ObjectPackageSurface::SYSTEMIC_FIELD_PACKS_CHECK,
                ObjectPackageSurface::TITLE_ALIAS_HARDENING_CHECK,
            ],
            requiredBackendArtifacts: [
                'composer.json',
                'resources/objecting/<BusinessStem>/object-field-packs.yaml',
                'resources/objecting/<BusinessStem>/object-backend-adoption.yaml',
                'resources/schema/<BusinessStem>/object-schema-mirror.yaml',
            ],
            targetFieldPacks: [
                ObjectFieldPackName::IDENTITY,
                ObjectFieldPackName::AUDIT,
                ObjectFieldPackName::TITLE,
                ObjectFieldPackName::STATE,
                ObjectFieldPackName::SOURCE,
                ObjectFieldPackName::FINGERPRINT,
                ObjectFieldPackName::SCOPE,
            ],
            titleAliasTokens: ['name', 'title', 'description', 'shortDescription', 'label', 'displayName'],
            deferredTokens: ['priority', 'visibility'],
            qualityGates: ['composer dump-autoload', 'composer test:quality', 'php tools/test/objecting_sibling_pilot_migration_handoff_check.php'],
            forbiddenActions: ['no full repository overwrite', 'no destructive repository cleanup', 'no /src/Domain/', 'no Port and Adapter pattern', 'no Symfony 7 constraints'],
        );

        $report = (new ObjectSiblingPilotMigrationHandoffReporter())->report($manifest);

        self::assertSame(ObjectSiblingPilotMigrationHandoffReport::STATUS_READY, $report->status());
        self::assertTrue($report->isReady());
        self::assertSame([], $report->blockingReasons());
        self::assertContains('objecting_rc2_baseline', $report->checks());
        self::assertContains('pilot_components', $report->checks());
    }

    public function testItBlocksUnsafeSiblingPilotMigrationHandoff(): void
    {
        $manifest = new ObjectSiblingPilotMigrationHandoffManifest(
            name: 'unsafe_sibling_pilot_migration_handoff',
            packageName: 'wrong/package',
            objectingBaseline: 'objecting_rc1',
            sourceAudit: 'workspace-objecting-field-pack-audit.md',
            pilotComponents: ['Addressing'],
            lockedObjectingArtifacts: [ObjectPackageSurface::RC2_MARKER_EXAMPLE],
            requiredBackendArtifacts: ['composer.json'],
            targetFieldPacks: [ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT, ObjectFieldPackName::TITLE],
            titleAliasTokens: ['title'],
            deferredTokens: ['priority'],
            qualityGates: ['composer dump-autoload'],
            forbiddenActions: ['no full repository overwrite'],
            objectingLocked: false,
            exposingLocked: false,
            siblingComponentsCanBeModified: false,
            touchedFilesOnly: false,
            cumulativeForBackupOnly: false,
            destructiveRepositoryCleanupForbidden: false,
        );

        $report = (new ObjectSiblingPilotMigrationHandoffReporter())->report($manifest);

        self::assertSame(ObjectSiblingPilotMigrationHandoffReport::STATUS_BLOCKED, $report->status());
        self::assertFalse($report->isReady());
        self::assertNotSame([], $report->blockingReasons());
    }
}
