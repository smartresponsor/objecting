<?php

declare(strict_types=1);

namespace App\Objecting\Reporter\Release;

use App\Objecting\Manifest\ObjectRc2MarkerManifest;
use App\Objecting\Report\ObjectRc2MarkerReport;
use App\Objecting\ReporterInterface\Release\ObjectRc2MarkerManifestReporterInterface;
use App\Objecting\Surface\ObjectPackageSurface;
use App\Objecting\ValueObject\ObjectFieldPackName;

final readonly class ObjectRc2MarkerManifestReporter implements ObjectRc2MarkerManifestReporterInterface
{
    public function report(ObjectRc2MarkerManifest $manifest): ObjectRc2MarkerReport
    {
        $blockingReasons = array_merge(
            self::identityReasons($manifest),
            self::pathReasons($manifest),
            self::collectionReasons($manifest),
            self::flagReasons($manifest),
        );

        return new ObjectRc2MarkerReport(
            $manifest,
            [
                'objecting_rc2_name',
                'objecting_rc2_previous_rc',
                'objecting_rc2_candidate',
                'objecting_package_name',
                'objecting_namespace_prefix',
                'objecting_bundle_class',
                'objecting_rc2_paths',
                'objecting_rc2_quality_gates',
                'objecting_rc2_composer_scripts',
                'objecting_rc2_final_entrypoints',
                'objecting_rc2_included_field_packs',
                'objecting_rc2_forbidden_field_packs',
                'objecting_rc2_deferred_tokens',
                'field_pack_foundation_only',
                'object_title_canonical',
                'legacy_free',
                'backend_runtime_owner',
                'exposing_separated',
                'rc_accepted',
            ],
            array_values(array_unique($blockingReasons)),
        );
    }

    /**
     * @return list<string>
     */
    private static function identityReasons(ObjectRc2MarkerManifest $manifest): array
    {
        $reasons = [];

        self::appendMismatch($reasons, $manifest->rcName(), 'objecting_rc2', 'RC2 marker name "%s" must be "%s".');
        self::appendMismatch($reasons, $manifest->previousRcName(), 'objecting_rc1', 'RC2 marker previous RC "%s" must be "%s".');
        self::appendMismatch($reasons, $manifest->rcCandidate(), 'objecting_wave25_rc2_marker', 'RC2 marker candidate "%s" must be "%s".');
        self::appendMismatch($reasons, $manifest->packageName(), ObjectPackageSurface::COMPOSER_PACKAGE, 'RC2 marker package "%s" must be "%s".');
        self::appendMismatch($reasons, $manifest->namespacePrefix(), ObjectPackageSurface::NAMESPACE_PREFIX, 'RC2 marker namespace prefix "%s" must be "%s".');
        self::appendMismatch($reasons, $manifest->bundleClass(), ObjectPackageSurface::BUNDLE_CLASS, 'RC2 marker bundle class "%s" must be "%s".');

        return $reasons;
    }

    /**
     * @return list<string>
     */
    private static function pathReasons(ObjectRc2MarkerManifest $manifest): array
    {
        $reasons = [];

        foreach ([
            'objecting_wave25_rc2_marker_cumulative.zip' => $manifest->cumulativeArchive(),
            'objecting_wave25_rc2_marker_touched.zip' => $manifest->touchedArchive(),
            'apply_objecting_wave25_rc2_marker_touched.ps1' => $manifest->applyScript(),
            ObjectPackageSurface::RELEASE_CLOSURE_EXAMPLE => $manifest->releaseClosurePath(),
            ObjectPackageSurface::FIELD_PACK_MANIFEST => $manifest->fieldPackManifestPath(),
            ObjectPackageSurface::TITLE_ALIAS_MANIFEST => $manifest->titleAliasManifestPath(),
            ObjectPackageSurface::BACKEND_MIGRATION_COMMAND_EXAMPLE => $manifest->backendMigrationCommandPath(),
            ObjectPackageSurface::BACKEND_CLONE_CLEANUP_EXAMPLE => $manifest->backendCloneCleanupPath(),
            ObjectPackageSurface::PLATFORM_CONSTRAINTS_EXAMPLE => $manifest->platformConstraintsPath(),
        ] as $expectedPath => $actualPath) {
            if ($actualPath !== $expectedPath) {
                $reasons[] = sprintf('RC2 marker path "%s" must equal "%s".', $actualPath, $expectedPath);
            }
        }

        return $reasons;
    }

    /**
     * @return list<string>
     */
    private static function collectionReasons(ObjectRc2MarkerManifest $manifest): array
    {
        return array_merge(
            self::missingReasons(
                ['composer dump-autoload', 'composer test:quality', 'composer test:rc2', 'php tools/test/objecting_rc2_check.php'],
                $manifest->qualityGates(),
                'RC2 marker quality gates must include "%s".',
            ),
            self::missingReasons(
                ['test:quality', 'test:rc', 'test:rc2', 'test:systemic-field-packs', 'test:title-alias-hardening', 'test:backend-migration-command', 'test:backend-clone-cleanup'],
                $manifest->requiredComposerScripts(),
                'RC2 marker required composer scripts must include "%s".',
            ),
            self::missingReasons(
                [
                    ObjectPackageSurface::RC2_MARKER_CHECK,
                    ObjectPackageSurface::SYSTEMIC_FIELD_PACKS_CHECK,
                    ObjectPackageSurface::TITLE_ALIAS_HARDENING_CHECK,
                    ObjectPackageSurface::BACKEND_MIGRATION_COMMAND_CHECK,
                    ObjectPackageSurface::BACKEND_CLONE_CLEANUP_CHECK,
                    ObjectPackageSurface::PLATFORM_CONSTRAINTS_CHECK,
                ],
                $manifest->finalEntrypoints(),
                'RC2 marker final entrypoints must include "%s".',
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
                $manifest->includedFieldPacks(),
                'RC2 marker included field packs must include "%s".',
            ),
            self::missingReasons(
                ['object_id', 'object_name', 'object_description', 'object_priority', 'object_visibility'],
                $manifest->forbiddenFieldPacks(),
                'RC2 marker forbidden field packs must include "%s".',
            ),
            self::missingReasons(
                ['priority', 'visibility'],
                $manifest->deferredTokens(),
                'RC2 marker deferred tokens must include "%s".',
            ),
        );
    }

    /**
     * @return list<string>
     */
    private static function flagReasons(ObjectRc2MarkerManifest $manifest): array
    {
        $reasons = [];

        if (!$manifest->fieldPackFoundationOnly()) {
            $reasons[] = 'RC2 marker must keep Objecting as a field-pack foundation only.';
        }
        if (!$manifest->objectTitleCanonical()) {
            $reasons[] = 'RC2 marker must keep object_title canonical.';
        }
        if (!$manifest->legacyFree()) {
            $reasons[] = 'RC2 marker must stay legacy-free.';
        }
        if (!$manifest->backendRuntimeOwner()) {
            $reasons[] = 'RC2 marker must keep backend components as runtime owners.';
        }
        if (!$manifest->exposingSeparated()) {
            $reasons[] = 'RC2 marker must keep Exposing as the separate API contract track.';
        }
        if (!$manifest->rcAccepted()) {
            $reasons[] = 'RC2 marker must be accepted as the Objecting RC2 dependency baseline.';
        }

        return $reasons;
    }

    /**
     * @param list<string> $reasons
     */
    private static function appendMismatch(array &$reasons, string $actual, string $expected, string $message): void
    {
        if ($actual !== $expected) {
            $reasons[] = sprintf($message, $actual, $expected);
        }
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
