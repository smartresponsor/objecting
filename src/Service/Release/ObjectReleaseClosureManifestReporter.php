<?php

declare(strict_types=1);

namespace App\Objecting\Service\Release;

use App\Objecting\ServiceInterface\Release\ObjectReleaseClosureManifestReporterInterface;
use App\Objecting\ValueObject\ObjectPackageSurface;
use App\Objecting\ValueObject\ObjectReleaseClosureManifest;
use App\Objecting\ValueObject\ObjectReleaseClosureReport;

final readonly class ObjectReleaseClosureManifestReporter implements ObjectReleaseClosureManifestReporterInterface
{
    public function report(ObjectReleaseClosureManifest $manifest): ObjectReleaseClosureReport
    {
        $checks = [];
        $blockingReasons = [];

        if (ObjectPackageSurface::COMPOSER_PACKAGE !== $manifest->packageName()) {
            $blockingReasons[] = sprintf('Release closure package "%s" must be "%s".', $manifest->packageName(), ObjectPackageSurface::COMPOSER_PACKAGE);
        }
        $checks[] = 'objecting_package_name';

        if (ObjectPackageSurface::NAMESPACE_PREFIX !== $manifest->namespacePrefix()) {
            $blockingReasons[] = sprintf('Release closure namespace prefix "%s" must be "%s".', $manifest->namespacePrefix(), ObjectPackageSurface::NAMESPACE_PREFIX);
        }
        $checks[] = 'objecting_namespace_prefix';

        if (ObjectPackageSurface::BUNDLE_CLASS !== $manifest->bundleClass()) {
            $blockingReasons[] = sprintf('Release closure bundle class "%s" must be "%s".', $manifest->bundleClass(), ObjectPackageSurface::BUNDLE_CLASS);
        }
        $checks[] = 'objecting_bundle_class';

        foreach (['composer dump-autoload', 'composer test:quality', 'composer test:rc-stabilization'] as $requiredGate) {
            if (!in_array($requiredGate, $manifest->qualityGates(), true)) {
                $blockingReasons[] = sprintf('Release closure quality gates must include "%s".', $requiredGate);
            }
        }
        $checks[] = 'release_closure_quality_gates';

        foreach ([
            ObjectPackageSurface::RELEASE_MANIFEST_EXAMPLE,
            ObjectPackageSurface::RELEASE_CLOSURE_EXAMPLE,
            ObjectPackageSurface::RC_STABILIZATION_EXAMPLE,
            ObjectPackageSurface::FIELD_PACK_MANIFEST,
            ObjectPackageSurface::TITLE_ALIAS_MANIFEST,
        ] as $requiredArtifact) {
            if (!in_array($requiredArtifact, $manifest->releaseArtifacts(), true)) {
                $blockingReasons[] = sprintf('Release closure artifacts must include "%s".', $requiredArtifact);
            }
        }
        $checks[] = 'release_closure_artifacts';

        foreach ([
            ObjectPackageSurface::CONSUMER_EXAMPLE,
            ObjectPackageSurface::BACKEND_MIGRATION_READINESS_EXAMPLE,
            ObjectPackageSurface::BACKEND_ADOPTION_EXAMPLE,
            ObjectPackageSurface::BACKEND_HANDOFF_EXAMPLE,
            ObjectPackageSurface::BACKEND_ADOPTION_PACKET_EXAMPLE,
            ObjectPackageSurface::EXPOSING_BRIDGE_EXAMPLE,
        ] as $requiredContract) {
            if (!in_array($requiredContract, $manifest->consumerContracts(), true)) {
                $blockingReasons[] = sprintf('Release closure consumer contracts must include "%s".', $requiredContract);
            }
        }
        $checks[] = 'release_closure_consumer_contracts';

        foreach (['backend_component_migration', 'exposing_api_contract'] as $requiredTrack) {
            if (!in_array($requiredTrack, $manifest->nextTracks(), true)) {
                $blockingReasons[] = sprintf('Release closure next tracks must include "%s".', $requiredTrack);
            }
        }
        $checks[] = 'release_closure_next_tracks';

        if (!$manifest->fieldPackFoundationOnly()) {
            $blockingReasons[] = 'Release closure manifest is not marked as field-pack-foundation-only.';
        }
        $checks[] = 'field_pack_foundation_only';

        if (!$manifest->objectTitleCanonical()) {
            $blockingReasons[] = 'Release closure manifest is not marked with canonical object title support.';
        }
        $checks[] = 'object_title_canonical';

        if (!$manifest->legacyFree()) {
            $blockingReasons[] = 'Release closure manifest is not marked legacy-free.';
        }
        $checks[] = 'legacy_free';

        if (!$manifest->backendRuntimeOwner()) {
            $blockingReasons[] = 'Release closure manifest must keep backend components as runtime owners.';
        }
        $checks[] = 'backend_runtime_owner';

        if (!$manifest->exposingSeparated()) {
            $blockingReasons[] = 'Release closure manifest must keep API contracts separated into Exposing.';
        }
        $checks[] = 'exposing_separated';

        return new ObjectReleaseClosureReport(
            manifest: $manifest,
            checks: $checks,
            blockingReasons: array_values(array_unique($blockingReasons)),
        );
    }
}
