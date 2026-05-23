<?php

declare(strict_types=1);

namespace App\Objecting\Service\Release;

use App\Objecting\ServiceInterface\Release\ObjectReleaseManifestReporterInterface;
use App\Objecting\ValueObject\ObjectPackageSurface;
use App\Objecting\ValueObject\ObjectReleaseManifest;
use App\Objecting\ValueObject\ObjectReleaseReport;

final readonly class ObjectReleaseManifestReporter implements ObjectReleaseManifestReporterInterface
{
    public function report(ObjectReleaseManifest $manifest): ObjectReleaseReport
    {
        $checks = [];
        $blockingReasons = [];

        if (ObjectPackageSurface::COMPOSER_PACKAGE !== $manifest->packageName()) {
            $blockingReasons[] = sprintf('Release package "%s" must be "%s".', $manifest->packageName(), ObjectPackageSurface::COMPOSER_PACKAGE);
        }
        $checks[] = 'objecting_package_name';

        if (ObjectPackageSurface::NAMESPACE_PREFIX !== $manifest->namespacePrefix()) {
            $blockingReasons[] = sprintf('Release namespace prefix "%s" must be "%s".', $manifest->namespacePrefix(), ObjectPackageSurface::NAMESPACE_PREFIX);
        }
        $checks[] = 'objecting_namespace_prefix';

        if (ObjectPackageSurface::BUNDLE_CLASS !== $manifest->bundleClass()) {
            $blockingReasons[] = sprintf('Release bundle class "%s" must be "%s".', $manifest->bundleClass(), ObjectPackageSurface::BUNDLE_CLASS);
        }
        $checks[] = 'objecting_bundle_class';

        foreach (['composer dump-autoload', 'composer test:quality'] as $requiredGate) {
            if (!in_array($requiredGate, $manifest->qualityGates(), true)) {
                $blockingReasons[] = sprintf('Release quality gates must include "%s".', $requiredGate);
            }
        }
        $checks[] = 'release_quality_gates';

        foreach (['test:canon', 'test:package-surface', 'test:release-readiness', 'test:quality'] as $requiredScript) {
            if (!in_array($requiredScript, $manifest->requiredComposerScripts(), true)) {
                $blockingReasons[] = sprintf('Release composer scripts must include "%s".', $requiredScript);
            }
        }
        $checks[] = 'release_composer_scripts';

        foreach ([
            ObjectPackageSurface::CONSUMER_EXAMPLE,
            ObjectPackageSurface::BACKEND_MIGRATION_READINESS_EXAMPLE,
            ObjectPackageSurface::BACKEND_ADOPTION_EXAMPLE,
            ObjectPackageSurface::BACKEND_HANDOFF_EXAMPLE,
        ] as $requiredContract) {
            if (!in_array($requiredContract, $manifest->consumerContracts(), true)) {
                $blockingReasons[] = sprintf('Release consumer contracts must include "%s".', $requiredContract);
            }
        }
        $checks[] = 'release_consumer_contracts';

        if (!$manifest->fieldPackFoundationOnly()) {
            $blockingReasons[] = 'Release manifest is not marked as field-pack-foundation-only.';
        }
        $checks[] = 'field_pack_foundation_only';

        if (!$manifest->legacyFree()) {
            $blockingReasons[] = 'Release manifest is not marked legacy-free.';
        }
        $checks[] = 'legacy_free';

        return new ObjectReleaseReport(
            manifest: $manifest,
            checks: $checks,
            blockingReasons: array_values(array_unique($blockingReasons)),
        );
    }
}
