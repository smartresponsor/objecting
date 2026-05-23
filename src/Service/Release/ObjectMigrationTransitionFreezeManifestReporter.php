<?php

declare(strict_types=1);

namespace App\Objecting\Service\Release;

use App\Objecting\ServiceInterface\Release\ObjectMigrationTransitionFreezeManifestReporterInterface;
use App\Objecting\ValueObject\ObjectMigrationTransitionFreezeManifest;
use App\Objecting\ValueObject\ObjectMigrationTransitionFreezeReport;
use App\Objecting\ValueObject\ObjectPackageSurface;

final readonly class ObjectMigrationTransitionFreezeManifestReporter implements ObjectMigrationTransitionFreezeManifestReporterInterface
{
    public function report(ObjectMigrationTransitionFreezeManifest $manifest): ObjectMigrationTransitionFreezeReport
    {
        $checks = [];
        $blockingReasons = [];

        if (ObjectPackageSurface::COMPOSER_PACKAGE !== $manifest->packageName()) {
            $blockingReasons[] = sprintf('Migration transition freeze package "%s" must be "%s".', $manifest->packageName(), ObjectPackageSurface::COMPOSER_PACKAGE);
        }
        $checks[] = 'objecting_package_name';

        if ('objecting_rc2' !== $manifest->objectingBaseline()) {
            $blockingReasons[] = 'Migration transition freeze must keep objecting_rc2 as the active locked baseline.';
        }
        $checks[] = 'objecting_rc2_baseline';

        if ('objecting_wave27_migration_transition_freeze' !== $manifest->closureCandidate()) {
            $blockingReasons[] = 'Migration transition freeze closure candidate must be objecting_wave27_migration_transition_freeze.';
        }
        $checks[] = 'closure_candidate';

        if ('backend_component_migration' !== $manifest->nextTrack()) {
            $blockingReasons[] = 'Migration transition freeze next track must be backend_component_migration.';
        }
        $checks[] = 'next_track';

        foreach (['Addressing', 'Taxating'] as $pilotComponent) {
            if (!in_array($pilotComponent, $manifest->pilotComponents(), true)) {
                $blockingReasons[] = sprintf('Migration transition freeze pilot components must include "%s".', $pilotComponent);
            }
        }
        $checks[] = 'pilot_components';

        foreach ([
            ObjectPackageSurface::RC2_MARKER_EXAMPLE,
            ObjectPackageSurface::SIBLING_PILOT_MIGRATION_HANDOFF_EXAMPLE,
            ObjectPackageSurface::BACKEND_MIGRATION_COMMAND_EXAMPLE,
            ObjectPackageSurface::BACKEND_CLONE_CLEANUP_EXAMPLE,
            ObjectPackageSurface::SYSTEMIC_FIELD_PACKS_CHECK,
            ObjectPackageSurface::TITLE_ALIAS_HARDENING_CHECK,
        ] as $requiredArtifact) {
            if (!in_array($requiredArtifact, $manifest->lockedObjectingArtifacts(), true)) {
                $blockingReasons[] = sprintf('Migration transition freeze locked artifacts must include "%s".', $requiredArtifact);
            }
        }
        $checks[] = 'locked_objecting_artifacts';

        foreach (['composer test:quality', 'composer test:sibling-pilot-migration-handoff', 'composer test:backend-migration-command'] as $requiredCommand) {
            if (!in_array($requiredCommand, $manifest->handoffCommands(), true)) {
                $blockingReasons[] = sprintf('Migration transition freeze handoff commands must include "%s".', $requiredCommand);
            }
        }
        $checks[] = 'handoff_commands';

        foreach (['no Objecting expansion during pilot migration', 'no Exposing changes during pilot migration', 'no full repository overwrite', 'no destructive repository cleanup', 'no Symfony 7 constraints'] as $forbiddenAction) {
            if (!in_array($forbiddenAction, $manifest->forbiddenActions(), true)) {
                $blockingReasons[] = sprintf('Migration transition freeze forbidden actions must include "%s".', $forbiddenAction);
            }
        }
        $checks[] = 'forbidden_actions';

        if (!$manifest->objectingFrozen()) {
            $blockingReasons[] = 'Migration transition freeze must mark Objecting as frozen for the next pilot migration wave.';
        }
        $checks[] = 'objecting_frozen';

        if (!$manifest->exposingFrozen()) {
            $blockingReasons[] = 'Migration transition freeze must mark Exposing as frozen for the next pilot migration wave.';
        }
        $checks[] = 'exposing_frozen';

        if (!$manifest->backendMigrationOpen()) {
            $blockingReasons[] = 'Migration transition freeze must open the backend component migration track.';
        }
        $checks[] = 'backend_migration_open';

        if (!$manifest->touchedFilesOnly()) {
            $blockingReasons[] = 'Migration transition freeze must require touched-files-only delivery for the next backend migration wave.';
        }
        $checks[] = 'touched_files_only';

        if (!$manifest->cumulativeForBackupOnly()) {
            $blockingReasons[] = 'Migration transition freeze must keep cumulative snapshots as backup/reference only.';
        }
        $checks[] = 'cumulative_for_backup_only';

        if (!$manifest->destructiveRepositoryCleanupForbidden()) {
            $blockingReasons[] = 'Migration transition freeze must forbid destructive repository cleanup.';
        }
        $checks[] = 'destructive_repository_cleanup_forbidden';

        return new ObjectMigrationTransitionFreezeReport(
            manifest: $manifest,
            checks: $checks,
            blockingReasons: array_values(array_unique($blockingReasons)),
        );
    }
}
