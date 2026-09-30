<?php

declare(strict_types=1);

namespace App\Objecting\Reporter\FieldPack;

use App\Objecting\Manifest\ObjectSiblingPilotMigrationHandoffManifest;
use App\Objecting\Report\ObjectSiblingPilotMigrationHandoffReport;
use App\Objecting\ReporterInterface\FieldPack\ObjectSiblingPilotMigrationHandoffReporterInterface;
use App\Objecting\Surface\ObjectPackageSurface;
use App\Objecting\ValueObject\ObjectFieldPackName;

final readonly class ObjectSiblingPilotMigrationHandoffReporter implements ObjectSiblingPilotMigrationHandoffReporterInterface
{
    public function report(ObjectSiblingPilotMigrationHandoffManifest $manifest): ObjectSiblingPilotMigrationHandoffReport
    {
        $blockingReasons = array_merge(
            self::identityReasons($manifest),
            self::collectionReasons($manifest),
            self::flagReasons($manifest),
        );

        return new ObjectSiblingPilotMigrationHandoffReport(
            manifest: $manifest,
            checks: [
                'objecting_package_name',
                'objecting_rc2_baseline',
                'pilot_components',
                'target_field_packs',
                'title_alias_tokens',
                'deferred_tokens',
                'locked_objecting_artifacts',
                'required_backend_artifacts',
                'quality_gates',
                'forbidden_actions',
                'objecting_locked',
                'exposing_locked',
                'sibling_components_can_be_modified',
                'touched_files_only',
                'cumulative_for_backup_only',
                'destructive_repository_cleanup_forbidden',
            ],
            blockingReasons: array_values(array_unique($blockingReasons)),
        );
    }

    /**
     * @return list<string>
     */
    private static function identityReasons(ObjectSiblingPilotMigrationHandoffManifest $manifest): array
    {
        $reasons = [];

        if (ObjectPackageSurface::COMPOSER_PACKAGE !== $manifest->packageName()) {
            $reasons[] = sprintf(
                'Sibling pilot migration handoff package "%s" must be "%s".',
                $manifest->packageName(),
                ObjectPackageSurface::COMPOSER_PACKAGE,
            );
        }

        if ('objecting_rc2' !== $manifest->objectingBaseline()) {
            $reasons[] = 'Sibling pilot migration handoff must use objecting_rc2 as the locked dependency baseline.';
        }

        return $reasons;
    }

    /**
     * @return list<string>
     */
    private static function collectionReasons(ObjectSiblingPilotMigrationHandoffManifest $manifest): array
    {
        return array_merge(
            self::missingReasons(
                ['Addressing', 'Taxating'],
                $manifest->pilotComponents(),
                'Sibling pilot migration handoff pilot components must include "%s".',
            ),
            self::missingReasons(
                [
                    ObjectFieldPackName::IDENTITY,
                    ObjectFieldPackName::AUDIT,
                    ObjectFieldPackName::TITLE,
                    ObjectFieldPackName::STATE,
                    ObjectFieldPackName::SOURCE,
                    ObjectFieldPackName::FINGERPRINT,
                ],
                $manifest->targetFieldPacks(),
                'Sibling pilot migration handoff target field packs must include "%s".',
            ),
            self::missingReasons(
                ['name', 'title', 'description', 'shortDescription', 'label', 'displayName'],
                $manifest->titleAliasTokens(),
                'Sibling pilot migration handoff title aliases must include "%s".',
            ),
            self::missingReasons(
                ['priority', 'visibility'],
                $manifest->deferredTokens(),
                'Sibling pilot migration handoff deferred tokens must include "%s".',
            ),
            self::missingReasons(
                [
                    ObjectPackageSurface::RC2_MARKER_EXAMPLE,
                    ObjectPackageSurface::BACKEND_MIGRATION_COMMAND_EXAMPLE,
                    ObjectPackageSurface::BACKEND_CLONE_CLEANUP_EXAMPLE,
                    ObjectPackageSurface::SYSTEMIC_FIELD_PACKS_CHECK,
                    ObjectPackageSurface::TITLE_ALIAS_HARDENING_CHECK,
                ],
                $manifest->lockedObjectingArtifacts(),
                'Sibling pilot migration handoff locked Objecting artifacts must include "%s".',
            ),
            self::missingReasons(
                [
                    'composer.json',
                    'resources/objecting/<BusinessStem>/object-field-packs.yaml',
                    'resources/objecting/<BusinessStem>/object-backend-adoption.yaml',
                    'resources/schema/<BusinessStem>/object-schema-mirror.yaml',
                ],
                $manifest->requiredBackendArtifacts(),
                'Sibling pilot migration handoff required backend artifacts must include "%s".',
            ),
            self::missingReasons(
                ['composer dump-autoload', 'composer test:quality', 'php tools/test/objecting_sibling_pilot_migration_handoff_check.php'],
                $manifest->qualityGates(),
                'Sibling pilot migration handoff quality gates must include "%s".',
            ),
            self::missingReasons(
                ['no full repository overwrite', 'no destructive repository cleanup', 'no /src/Domain/', 'no Port and Adapter pattern', 'no Symfony 7 constraints'],
                $manifest->forbiddenActions(),
                'Sibling pilot migration handoff forbidden actions must include "%s".',
            ),
        );
    }

    /**
     * @return list<string>
     */
    private static function flagReasons(ObjectSiblingPilotMigrationHandoffManifest $manifest): array
    {
        $reasons = [];

        if (!$manifest->objectingLocked()) {
            $reasons[] = 'Sibling pilot migration handoff must lock Objecting and forbid Objecting changes during sibling migration.';
        }
        if (!$manifest->exposingLocked()) {
            $reasons[] = 'Sibling pilot migration handoff must lock Exposing and forbid API contract changes during sibling migration.';
        }
        if (!$manifest->siblingComponentsCanBeModified()) {
            $reasons[] = 'Sibling pilot migration handoff must allow sibling backend component changes.';
        }
        if (!$manifest->touchedFilesOnly()) {
            $reasons[] = 'Sibling pilot migration handoff must require touched-files-only delivery.';
        }
        if (!$manifest->cumulativeForBackupOnly()) {
            $reasons[] = 'Sibling pilot migration handoff must mark cumulative snapshots as backup/reference only.';
        }
        if (!$manifest->destructiveRepositoryCleanupForbidden()) {
            $reasons[] = 'Sibling pilot migration handoff must forbid destructive repository cleanup.';
        }

        return $reasons;
    }

    /**
     * @param list<string> $required
     * @param list<string> $actual
     *
     * @return list<string>
     */
    private static function missingReasons(array $required, array $actual, string $message): array
    {
        $reasons = [];

        foreach ($required as $value) {
            if (!in_array($value, $actual, true)) {
                $reasons[] = sprintf($message, $value);
            }
        }

        return $reasons;
    }
}
