<?php

declare(strict_types=1);

namespace App\Objecting\Service\Release;

use App\Objecting\ServiceInterface\Release\ObjectRcMarkerManifestReporterInterface;
use App\Objecting\ValueObject\ObjectPackageSurface;
use App\Objecting\ValueObject\ObjectRcMarkerManifest;
use App\Objecting\ValueObject\ObjectRcMarkerReport;

final readonly class ObjectRcMarkerManifestReporter implements ObjectRcMarkerManifestReporterInterface
{
    public function report(ObjectRcMarkerManifest $manifest): ObjectRcMarkerReport
    {
        $checks = [];
        $blockingReasons = [];

        if ('objecting_rc1' !== $manifest->rcName()) {
            $blockingReasons[] = sprintf('RC marker nameEntity "%s" must be "objecting_rc1".', $manifest->rcName());
        }
        $checks[] = 'objecting_rc_name';

        if ('objecting_wave20_platform_constraints' !== $manifest->rcCandidate()) {
            $blockingReasons[] = sprintf('RC marker candidate "%s" must be "objecting_wave20_platform_constraints".', $manifest->rcCandidate());
        }
        $checks[] = 'objecting_rc_candidate';

        if (ObjectPackageSurface::COMPOSER_PACKAGE !== $manifest->packageName()) {
            $blockingReasons[] = sprintf('RC marker package "%s" must be "%s".', $manifest->packageName(), ObjectPackageSurface::COMPOSER_PACKAGE);
        }
        $checks[] = 'objecting_package_name';

        if (ObjectPackageSurface::NAMESPACE_PREFIX !== $manifest->namespacePrefix()) {
            $blockingReasons[] = sprintf('RC marker namespace prefix "%s" must be "%s".', $manifest->namespacePrefix(), ObjectPackageSurface::NAMESPACE_PREFIX);
        }
        $checks[] = 'objecting_namespace_prefix';

        if (ObjectPackageSurface::BUNDLE_CLASS !== $manifest->bundleClass()) {
            $blockingReasons[] = sprintf('RC marker bundle class "%s" must be "%s".', $manifest->bundleClass(), ObjectPackageSurface::BUNDLE_CLASS);
        }
        $checks[] = 'objecting_bundle_class';

        foreach ([
            'objecting_wave20_platform_constraints_cumulative.zip' => $manifest->cumulativeArchive(),
            'objecting_wave20_platform_constraints_touched.zip' => $manifest->touchedArchive(),
            'apply_objecting_wave20_platform_constraints_touched.ps1' => $manifest->applyScript(),
            ObjectPackageSurface::RC_STABILIZATION_EXAMPLE => $manifest->rcStabilizationPath(),
            ObjectPackageSurface::RELEASE_CLOSURE_EXAMPLE => $manifest->releaseClosurePath(),
            ObjectPackageSurface::RELEASE_MANIFEST_EXAMPLE => $manifest->releaseReadinessPath(),
            ObjectPackageSurface::BACKEND_IMPORT_EXAMPLE => $manifest->backendImportPath(),
            ObjectPackageSurface::BACKEND_ADOPTION_PACKET_EXAMPLE => $manifest->adoptionPacketPath(),
            ObjectPackageSurface::EXPOSING_BRIDGE_EXAMPLE => $manifest->exposingBridgePath(),
            ObjectPackageSurface::SCHEMA_MIRROR_EXAMPLE => $manifest->schemaMirrorPath(),
            ObjectPackageSurface::DOCTRINE_MAPPING_EXAMPLE => $manifest->doctrineMappingPath(),
        ] as $expectedPath => $actualPath) {
            if ($actualPath !== $expectedPath) {
                $blockingReasons[] = sprintf('RC marker path "%s" must equal "%s".', $actualPath, $expectedPath);
            }
        }
        $checks[] = 'objecting_rc_paths';

        foreach (['composer dump-autoload', 'composer test:quality', 'composer test:platform-constraints', 'composer test:rc', 'php tools/test/objecting_rc1_check.php', 'php tools/test/objecting_platform_constraint_check.php'] as $requiredGate) {
            if (!in_array($requiredGate, $manifest->qualityGates(), true)) {
                $blockingReasons[] = sprintf('RC marker quality gates must include "%s".', $requiredGate);
            }
        }
        $checks[] = 'objecting_rc_quality_gates';

        foreach (['test:quality', 'test:rc', 'test:rc1', 'test:rc-stabilization', 'test:release-closure', 'test:backend-import', 'test:platform-constraints'] as $requiredScript) {
            if (!in_array($requiredScript, $manifest->requiredComposerScripts(), true)) {
                $blockingReasons[] = sprintf('RC marker required composer scripts must include "%s".', $requiredScript);
            }
        }
        $checks[] = 'objecting_rc_composer_scripts';

        foreach ([
            ObjectPackageSurface::RC_MARKER_CHECK,
            ObjectPackageSurface::RC_STABILIZATION_CHECK,
            ObjectPackageSurface::RELEASE_CLOSURE_CHECK,
            ObjectPackageSurface::BACKEND_IMPORT_CHECK,
            ObjectPackageSurface::EXPOSING_BRIDGE_CHECK,
            ObjectPackageSurface::SCHEMA_MIRROR_CHECK,
            ObjectPackageSurface::DOCTRINE_MAPPING_CHECK,
            ObjectPackageSurface::PLATFORM_CONSTRAINTS_CHECK,
        ] as $requiredEntrypoint) {
            if (!in_array($requiredEntrypoint, $manifest->finalEntrypoints(), true)) {
                $blockingReasons[] = sprintf('RC marker final entrypoints must include "%s".', $requiredEntrypoint);
            }
        }
        $checks[] = 'objecting_rc_final_entrypoints';

        if (!$manifest->fieldPackFoundationOnly()) {
            $blockingReasons[] = 'RC marker must keep Objecting as a field-pack foundation only.';
        }
        $checks[] = 'field_pack_foundation_only';

        if (!$manifest->objectTitleCanonical()) {
            $blockingReasons[] = 'RC marker must keep object_title canonical.';
        }
        $checks[] = 'object_title_canonical';

        if (!$manifest->legacyFree()) {
            $blockingReasons[] = 'RC marker must stay legacy-free.';
        }
        $checks[] = 'legacy_free';

        if (!$manifest->backendRuntimeOwner()) {
            $blockingReasons[] = 'RC marker must keep backend components as runtime owners.';
        }
        $checks[] = 'backend_runtime_owner';

        if (!$manifest->exposingSeparated()) {
            $blockingReasons[] = 'RC marker must keep Exposing as the separate API contract track.';
        }
        $checks[] = 'exposing_separated';

        if (!$manifest->rcAccepted()) {
            $blockingReasons[] = 'RC marker must be accepted as the Objecting RC1 dependency baseline.';
        }
        $checks[] = 'rc_accepted';

        return new ObjectRcMarkerReport($manifest, $checks, array_values(array_unique($blockingReasons)));
    }
}
