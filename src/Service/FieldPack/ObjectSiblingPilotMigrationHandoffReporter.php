<?php

declare(strict_types=1);

namespace App\Objecting\Service\FieldPack;

use App\Objecting\ServiceInterface\FieldPack\ObjectSiblingPilotMigrationHandoffReporterInterface;
use App\Objecting\ValueObject\ObjectFieldPackName;
use App\Objecting\ValueObject\ObjectPackageSurface;
use App\Objecting\ValueObject\ObjectSiblingPilotMigrationHandoffManifest;
use App\Objecting\ValueObject\ObjectSiblingPilotMigrationHandoffReport;

final readonly class ObjectSiblingPilotMigrationHandoffReporter implements ObjectSiblingPilotMigrationHandoffReporterInterface
{
    public function report(ObjectSiblingPilotMigrationHandoffManifest $manifest): ObjectSiblingPilotMigrationHandoffReport
    {
        $checks = [];
        $blockingReasons = [];

        if (ObjectPackageSurface::COMPOSER_PACKAGE !== $manifest->packageName()) {
            $blockingReasons[] = sprintf('Sibling pilot migration handoff package "%s" must be "%s".', $manifest->packageName(), ObjectPackageSurface::COMPOSER_PACKAGE);
        }
        $checks[] = 'objecting_package_name';

        if ('objecting_rc2' !== $manifest->objectingBaseline()) {
            $blockingReasons[] = 'Sibling pilot migration handoff must use objecting_rc2 as the locked dependency baseline.';
        }
        $checks[] = 'objecting_rc2_baseline';

        foreach (['Addressing', 'Taxating'] as $pilotComponent) {
            if (!in_array($pilotComponent, $manifest->pilotComponents(), true)) {
                $blockingReasons[] = sprintf('Sibling pilot migration handoff pilot components must include "%s".', $pilotComponent);
            }
        }
        $checks[] = 'pilot_components';

        foreach ([
            ObjectFieldPackName::IDENTITY,
            ObjectFieldPackName::AUDIT,
            ObjectFieldPackName::TITLE,
            ObjectFieldPackName::STATE,
            ObjectFieldPackName::SOURCE,
            ObjectFieldPackName::FINGERPRINT,
            ObjectFieldPackName::SCOPE,
        ] as $requiredPack) {
            if (!in_array($requiredPack, $manifest->targetFieldPacks(), true)) {
                $blockingReasons[] = sprintf('Sibling pilot migration handoff target field packs must include "%s".', $requiredPack);
            }
        }
        $checks[] = 'target_field_packs';

        foreach (['name', 'title', 'description', 'shortDescription', 'label', 'displayName'] as $aliasToken) {
            if (!in_array($aliasToken, $manifest->titleAliasTokens(), true)) {
                $blockingReasons[] = sprintf('Sibling pilot migration handoff title aliases must include "%s".', $aliasToken);
            }
        }
        $checks[] = 'title_alias_tokens';

        foreach (['priority', 'visibility'] as $deferredToken) {
            if (!in_array($deferredToken, $manifest->deferredTokens(), true)) {
                $blockingReasons[] = sprintf('Sibling pilot migration handoff deferred tokens must include "%s".', $deferredToken);
            }
        }
        $checks[] = 'deferred_tokens';

        foreach ([
            ObjectPackageSurface::RC2_MARKER_EXAMPLE,
            ObjectPackageSurface::BACKEND_MIGRATION_COMMAND_EXAMPLE,
            ObjectPackageSurface::BACKEND_CLONE_CLEANUP_EXAMPLE,
            ObjectPackageSurface::SYSTEMIC_FIELD_PACKS_CHECK,
            ObjectPackageSurface::TITLE_ALIAS_HARDENING_CHECK,
        ] as $requiredArtifact) {
            if (!in_array($requiredArtifact, $manifest->lockedObjectingArtifacts(), true)) {
                $blockingReasons[] = sprintf('Sibling pilot migration handoff locked Objecting artifacts must include "%s".', $requiredArtifact);
            }
        }
        $checks[] = 'locked_objecting_artifacts';

        foreach ([
            'composer.json',
            'resources/objecting/<BusinessStem>/object-field-packs.yaml',
            'resources/objecting/<BusinessStem>/object-backend-adoption.yaml',
            'resources/schema/<BusinessStem>/object-schema-mirror.yaml',
        ] as $requiredBackendArtifact) {
            if (!in_array($requiredBackendArtifact, $manifest->requiredBackendArtifacts(), true)) {
                $blockingReasons[] = sprintf('Sibling pilot migration handoff required backend artifacts must include "%s".', $requiredBackendArtifact);
            }
        }
        $checks[] = 'required_backend_artifacts';

        foreach (['composer dump-autoload', 'composer test:quality', 'php tools/test/objecting_sibling_pilot_migration_handoff_check.php'] as $requiredGate) {
            if (!in_array($requiredGate, $manifest->qualityGates(), true)) {
                $blockingReasons[] = sprintf('Sibling pilot migration handoff quality gates must include "%s".', $requiredGate);
            }
        }
        $checks[] = 'quality_gates';

        foreach (['no full repository overwrite', 'no destructive repository cleanup', 'no /src/Domain/', 'no Port and Adapter pattern', 'no Symfony 7 constraints'] as $forbiddenAction) {
            if (!in_array($forbiddenAction, $manifest->forbiddenActions(), true)) {
                $blockingReasons[] = sprintf('Sibling pilot migration handoff forbidden actions must include "%s".', $forbiddenAction);
            }
        }
        $checks[] = 'forbidden_actions';

        if (!$manifest->objectingLocked()) {
            $blockingReasons[] = 'Sibling pilot migration handoff must lock Objecting and forbid Objecting changes during sibling migration.';
        }
        $checks[] = 'objecting_locked';

        if (!$manifest->exposingLocked()) {
            $blockingReasons[] = 'Sibling pilot migration handoff must lock Exposing and forbid API contract changes during sibling migration.';
        }
        $checks[] = 'exposing_locked';

        if (!$manifest->siblingComponentsCanBeModified()) {
            $blockingReasons[] = 'Sibling pilot migration handoff must allow sibling backend component changes.';
        }
        $checks[] = 'sibling_components_can_be_modified';

        if (!$manifest->touchedFilesOnly()) {
            $blockingReasons[] = 'Sibling pilot migration handoff must require touched-files-only delivery.';
        }
        $checks[] = 'touched_files_only';

        if (!$manifest->cumulativeForBackupOnly()) {
            $blockingReasons[] = 'Sibling pilot migration handoff must mark cumulative snapshots as backup/reference only.';
        }
        $checks[] = 'cumulative_for_backup_only';

        if (!$manifest->destructiveRepositoryCleanupForbidden()) {
            $blockingReasons[] = 'Sibling pilot migration handoff must forbid destructive repository cleanup.';
        }
        $checks[] = 'destructive_repository_cleanup_forbidden';

        return new ObjectSiblingPilotMigrationHandoffReport(
            manifest: $manifest,
            checks: $checks,
            blockingReasons: array_values(array_unique($blockingReasons)),
        );
    }
}
