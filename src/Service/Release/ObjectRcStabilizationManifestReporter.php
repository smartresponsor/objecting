<?php

declare(strict_types=1);

namespace App\Objecting\Service\Release;

use App\Objecting\ServiceInterface\Release\ObjectRcStabilizationManifestReporterInterface;
use App\Objecting\ValueObject\ObjectPackageSurface;
use App\Objecting\ValueObject\ObjectRcStabilizationManifest;
use App\Objecting\ValueObject\ObjectRcStabilizationReport;

final readonly class ObjectRcStabilizationManifestReporter implements ObjectRcStabilizationManifestReporterInterface
{
    public function report(ObjectRcStabilizationManifest $manifest): ObjectRcStabilizationReport
    {
        $checks = [];
        $blockingReasons = [];

        if (ObjectPackageSurface::COMPOSER_PACKAGE !== $manifest->packageName()) {
            $blockingReasons[] = sprintf('RC stabilization package "%s" must be "%s".', $manifest->packageName(), ObjectPackageSurface::COMPOSER_PACKAGE);
        }
        $checks[] = 'objecting_package_name';

        if (ObjectPackageSurface::NAMESPACE_PREFIX !== $manifest->namespacePrefix()) {
            $blockingReasons[] = sprintf('RC stabilization namespace prefix "%s" must be "%s".', $manifest->namespacePrefix(), ObjectPackageSurface::NAMESPACE_PREFIX);
        }
        $checks[] = 'objecting_namespace_prefix';

        if (ObjectPackageSurface::BUNDLE_CLASS !== $manifest->bundleClass()) {
            $blockingReasons[] = sprintf('RC stabilization bundle class "%s" must be "%s".', $manifest->bundleClass(), ObjectPackageSurface::BUNDLE_CLASS);
        }
        $checks[] = 'objecting_bundle_class';

        foreach ([
            ObjectPackageSurface::RELEASE_CLOSURE_EXAMPLE => $manifest->releaseClosurePath(),
            ObjectPackageSurface::RELEASE_MANIFEST_EXAMPLE => $manifest->releaseReadinessPath(),
            ObjectPackageSurface::BACKEND_IMPORT_EXAMPLE => $manifest->backendImportPath(),
            ObjectPackageSurface::BACKEND_ADOPTION_PACKET_EXAMPLE => $manifest->adoptionPacketPath(),
            ObjectPackageSurface::EXPOSING_BRIDGE_EXAMPLE => $manifest->exposingBridgePath(),
            ObjectPackageSurface::SCHEMA_MIRROR_EXAMPLE => $manifest->schemaMirrorPath(),
            ObjectPackageSurface::DOCTRINE_MAPPING_EXAMPLE => $manifest->doctrineMappingPath(),
        ] as $expectedPath => $actualPath) {
            if ($actualPath !== $expectedPath) {
                $blockingReasons[] = sprintf('RC stabilization path "%s" must equal "%s".', $actualPath, $expectedPath);
            }
        }
        $checks[] = 'rc_stabilization_contract_paths';

        foreach (['composer dump-autoload', 'composer test:quality', 'php tools/test/objecting_rc_stabilization_check.php'] as $requiredGate) {
            if (!in_array($requiredGate, $manifest->qualityGates(), true)) {
                $blockingReasons[] = sprintf('RC stabilization quality gates must include "%s".', $requiredGate);
            }
        }
        $checks[] = 'rc_stabilization_quality_gates';

        foreach (['test:quality', 'test:rc-stabilization', 'test:release-closure', 'test:backend-import'] as $requiredScript) {
            if (!in_array($requiredScript, $manifest->requiredComposerScripts(), true)) {
                $blockingReasons[] = sprintf('RC stabilization required composer scripts must include "%s".', $requiredScript);
            }
        }
        $checks[] = 'rc_stabilization_composer_scripts';

        foreach ([
            ObjectPackageSurface::RELEASE_CLOSURE_CHECK,
            ObjectPackageSurface::BACKEND_IMPORT_CHECK,
            ObjectPackageSurface::EXPOSING_BRIDGE_CHECK,
            ObjectPackageSurface::SCHEMA_MIRROR_CHECK,
            ObjectPackageSurface::DOCTRINE_MAPPING_CHECK,
            ObjectPackageSurface::RC_STABILIZATION_CHECK,
        ] as $requiredEntrypoint) {
            if (!in_array($requiredEntrypoint, $manifest->finalEntrypoints(), true)) {
                $blockingReasons[] = sprintf('RC stabilization final entrypoints must include "%s".', $requiredEntrypoint);
            }
        }
        $checks[] = 'rc_stabilization_final_entrypoints';

        if (!$manifest->fieldPackFoundationOnly()) {
            $blockingReasons[] = 'RC stabilization must keep Objecting as a field-pack foundation only.';
        }
        $checks[] = 'field_pack_foundation_only';

        if (!$manifest->objectTitleCanonical()) {
            $blockingReasons[] = 'RC stabilization must keep object_title canonical.';
        }
        $checks[] = 'object_title_canonical';

        if (!$manifest->legacyFree()) {
            $blockingReasons[] = 'RC stabilization must stay legacy-free.';
        }
        $checks[] = 'legacy_free';

        if (!$manifest->backendRuntimeOwner()) {
            $blockingReasons[] = 'RC stabilization must keep backend components as runtime owners.';
        }
        $checks[] = 'backend_runtime_owner';

        if (!$manifest->exposingSeparated()) {
            $blockingReasons[] = 'RC stabilization must keep Exposing as the separate API contract track.';
        }
        $checks[] = 'exposing_separated';

        if (!$manifest->rcMarkerPending()) {
            $blockingReasons[] = 'RC stabilization must mark the RC marker as pending for the next wave.';
        }
        $checks[] = 'rc_marker_pending';

        return new ObjectRcStabilizationReport($manifest, $checks, array_values(array_unique($blockingReasons)));
    }
}
