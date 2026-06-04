<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Manifest\ObjectMigrationTransitionFreezeManifest;
use App\Objecting\Report\ObjectMigrationTransitionFreezeReport;
use App\Objecting\Reporter\Release\ObjectMigrationTransitionFreezeManifestReporter;
use App\Objecting\Surface\ObjectPackageSurface;
use PHPUnit\Framework\TestCase;

final class ObjectMigrationTransitionFreezeManifestReporterTest extends TestCase
{
    public function testItMarksCanonicalMigrationTransitionFreezeReady(): void
    {
        $manifest = new ObjectMigrationTransitionFreezeManifest(
            name: 'objecting_migration_transition_freeze',
            packageName: ObjectPackageSurface::COMPOSER_PACKAGE,
            objectingBaseline: 'objecting_rc2',
            closureCandidate: 'objecting_wave27_migration_transition_freeze',
            cumulativeArchive: 'objecting_wave27_migration_transition_freeze_cumulative.zip',
            touchedArchive: 'objecting_wave27_migration_transition_freeze_touched.zip',
            nextTrack: 'backend_component_migration',
            pilotComponents: ['Addressing', 'Taxating'],
            lockedObjectingArtifacts: [
                ObjectPackageSurface::RC2_MARKER_EXAMPLE,
                ObjectPackageSurface::SIBLING_PILOT_MIGRATION_HANDOFF_EXAMPLE,
                ObjectPackageSurface::BACKEND_MIGRATION_COMMAND_EXAMPLE,
                ObjectPackageSurface::BACKEND_CLONE_CLEANUP_EXAMPLE,
                ObjectPackageSurface::SYSTEMIC_FIELD_PACKS_CHECK,
                ObjectPackageSurface::TITLE_ALIAS_HARDENING_CHECK,
            ],
            handoffCommands: ['composer test:quality', 'composer test:sibling-pilot-migration-handoff', 'composer test:backend-migration-command'],
            forbiddenActions: ['no Objecting expansion during pilot migration', 'no Exposing changes during pilot migration', 'no full repository overwrite', 'no destructive repository cleanup', 'no Symfony 7 constraints'],
        );

        $report = (new ObjectMigrationTransitionFreezeManifestReporter())->report($manifest);

        self::assertSame(ObjectMigrationTransitionFreezeReport::STATUS_READY, $report->status());
        self::assertTrue($report->isReady());
        self::assertSame([], $report->blockingReasons());
        self::assertContains('objecting_frozen', $report->checks());
        self::assertContains('backend_migration_open', $report->checks());
    }

    public function testItBlocksIncompleteMigrationTransitionFreeze(): void
    {
        $manifest = new ObjectMigrationTransitionFreezeManifest(
            name: 'objecting_migration_transition_freeze',
            packageName: 'wrong/package',
            objectingBaseline: 'objecting_rc1',
            closureCandidate: 'objecting_wave27_wrong',
            cumulativeArchive: 'objecting_wave27_migration_transition_freeze_cumulative.zip',
            touchedArchive: 'objecting_wave27_migration_transition_freeze_touched.zip',
            nextTrack: 'objecting_expansion',
            pilotComponents: ['Addressing'],
            lockedObjectingArtifacts: [ObjectPackageSurface::RC2_MARKER_EXAMPLE],
            handoffCommands: ['composer test:quality'],
            forbiddenActions: ['no full repository overwrite'],
            objectingFrozen: false,
            exposingFrozen: false,
            backendMigrationOpen: false,
            touchedFilesOnly: false,
            cumulativeForBackupOnly: false,
            destructiveRepositoryCleanupForbidden: false,
        );

        $report = (new ObjectMigrationTransitionFreezeManifestReporter())->report($manifest);

        self::assertSame(ObjectMigrationTransitionFreezeReport::STATUS_BLOCKED, $report->status());
        self::assertFalse($report->isReady());
        self::assertNotSame([], $report->blockingReasons());
    }
}
