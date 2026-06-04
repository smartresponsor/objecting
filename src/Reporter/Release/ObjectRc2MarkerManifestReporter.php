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
        $checks = [];
        $blockingReasons = [];

        if ('objecting_rc2' !== $manifest->rcName()) {
            $blockingReasons[] = sprintf('RC2 marker name "%s" must be "objecting_rc2".', $manifest->rcName());
        }
        $checks[] = 'objecting_rc2_name';

        if ('objecting_rc1' !== $manifest->previousRcName()) {
            $blockingReasons[] = sprintf('RC2 marker previous RC "%s" must be "objecting_rc1".', $manifest->previousRcName());
        }
        $checks[] = 'objecting_rc2_previous_rc';

        if ('objecting_wave25_rc2_marker' !== $manifest->rcCandidate()) {
            $blockingReasons[] = sprintf('RC2 marker candidate "%s" must be "objecting_wave25_rc2_marker".', $manifest->rcCandidate());
        }
        $checks[] = 'objecting_rc2_candidate';

        if (ObjectPackageSurface::COMPOSER_PACKAGE !== $manifest->packageName()) {
            $blockingReasons[] = sprintf('RC2 marker package "%s" must be "%s".', $manifest->packageName(), ObjectPackageSurface::COMPOSER_PACKAGE);
        }
        $checks[] = 'objecting_package_name';

        if (ObjectPackageSurface::NAMESPACE_PREFIX !== $manifest->namespacePrefix()) {
            $blockingReasons[] = sprintf('RC2 marker namespace prefix "%s" must be "%s".', $manifest->namespacePrefix(), ObjectPackageSurface::NAMESPACE_PREFIX);
        }
        $checks[] = 'objecting_namespace_prefix';

        if (ObjectPackageSurface::BUNDLE_CLASS !== $manifest->bundleClass()) {
            $blockingReasons[] = sprintf('RC2 marker bundle class "%s" must be "%s".', $manifest->bundleClass(), ObjectPackageSurface::BUNDLE_CLASS);
        }
        $checks[] = 'objecting_bundle_class';

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
                $blockingReasons[] = sprintf('RC2 marker path "%s" must equal "%s".', $actualPath, $expectedPath);
            }
        }
        $checks[] = 'objecting_rc2_paths';

        foreach (['composer dump-autoload', 'composer test:quality', 'composer test:rc2', 'php tools/test/objecting_rc2_check.php'] as $requiredGate) {
            if (!in_array($requiredGate, $manifest->qualityGates(), true)) {
                $blockingReasons[] = sprintf('RC2 marker quality gates must include "%s".', $requiredGate);
            }
        }
        $checks[] = 'objecting_rc2_quality_gates';

        foreach (['test:quality', 'test:rc', 'test:rc2', 'test:systemic-field-packs', 'test:title-alias-hardening', 'test:backend-migration-command', 'test:backend-clone-cleanup'] as $requiredScript) {
            if (!in_array($requiredScript, $manifest->requiredComposerScripts(), true)) {
                $blockingReasons[] = sprintf('RC2 marker required composer scripts must include "%s".', $requiredScript);
            }
        }
        $checks[] = 'objecting_rc2_composer_scripts';

        foreach ([
            ObjectPackageSurface::RC2_MARKER_CHECK,
            ObjectPackageSurface::SYSTEMIC_FIELD_PACKS_CHECK,
            ObjectPackageSurface::TITLE_ALIAS_HARDENING_CHECK,
            ObjectPackageSurface::BACKEND_MIGRATION_COMMAND_CHECK,
            ObjectPackageSurface::BACKEND_CLONE_CLEANUP_CHECK,
            ObjectPackageSurface::PLATFORM_CONSTRAINTS_CHECK,
        ] as $requiredEntrypoint) {
            if (!in_array($requiredEntrypoint, $manifest->finalEntrypoints(), true)) {
                $blockingReasons[] = sprintf('RC2 marker final entrypoints must include "%s".', $requiredEntrypoint);
            }
        }
        $checks[] = 'objecting_rc2_final_entrypoints';

        foreach ([
            ObjectFieldPackName::IDENTITY,
            ObjectFieldPackName::AUDIT,
            ObjectFieldPackName::TITLE,
            ObjectFieldPackName::STATE,
            ObjectFieldPackName::SOURCE,
            ObjectFieldPackName::FINGERPRINT,
            ObjectFieldPackName::SCOPE,
        ] as $requiredPack) {
            if (!in_array($requiredPack, $manifest->includedFieldPacks(), true)) {
                $blockingReasons[] = sprintf('RC2 marker included field packs must include "%s".', $requiredPack);
            }
        }
        $checks[] = 'objecting_rc2_included_field_packs';

        foreach (['object_id', 'object_name', 'object_description', 'object_priority', 'object_visibility'] as $forbiddenPack) {
            if (!in_array($forbiddenPack, $manifest->forbiddenFieldPacks(), true)) {
                $blockingReasons[] = sprintf('RC2 marker forbidden field packs must include "%s".', $forbiddenPack);
            }
        }
        $checks[] = 'objecting_rc2_forbidden_field_packs';

        foreach (['priority', 'visibility'] as $deferredToken) {
            if (!in_array($deferredToken, $manifest->deferredTokens(), true)) {
                $blockingReasons[] = sprintf('RC2 marker deferred tokens must include "%s".', $deferredToken);
            }
        }
        $checks[] = 'objecting_rc2_deferred_tokens';

        if (!$manifest->fieldPackFoundationOnly()) {
            $blockingReasons[] = 'RC2 marker must keep Objecting as a field-pack foundation only.';
        }
        $checks[] = 'field_pack_foundation_only';

        if (!$manifest->objectTitleCanonical()) {
            $blockingReasons[] = 'RC2 marker must keep object_title canonical.';
        }
        $checks[] = 'object_title_canonical';

        if (!$manifest->legacyFree()) {
            $blockingReasons[] = 'RC2 marker must stay legacy-free.';
        }
        $checks[] = 'legacy_free';

        if (!$manifest->backendRuntimeOwner()) {
            $blockingReasons[] = 'RC2 marker must keep backend components as runtime owners.';
        }
        $checks[] = 'backend_runtime_owner';

        if (!$manifest->exposingSeparated()) {
            $blockingReasons[] = 'RC2 marker must keep Exposing as the separate API contract track.';
        }
        $checks[] = 'exposing_separated';

        if (!$manifest->rcAccepted()) {
            $blockingReasons[] = 'RC2 marker must be accepted as the Objecting RC2 dependency baseline.';
        }
        $checks[] = 'rc_accepted';

        return new ObjectRc2MarkerReport($manifest, $checks, array_values(array_unique($blockingReasons)));
    }
}
