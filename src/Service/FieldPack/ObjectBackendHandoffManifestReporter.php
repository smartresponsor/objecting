<?php

declare(strict_types=1);

namespace App\Objecting\Service\FieldPack;

use App\Objecting\ServiceInterface\FieldPack\ObjectBackendHandoffManifestReporterInterface;
use App\Objecting\ValueObject\ObjectBackendHandoffManifest;
use App\Objecting\ValueObject\ObjectBackendHandoffReport;
use App\Objecting\ValueObject\ObjectPackageSurface;

final readonly class ObjectBackendHandoffManifestReporter implements ObjectBackendHandoffManifestReporterInterface
{
    public function report(ObjectBackendHandoffManifest $manifest): ObjectBackendHandoffReport
    {
        $checks = [];
        $blockingReasons = [];

        if (ObjectPackageSurface::COMPOSER_PACKAGE !== $manifest->packageName()) {
            $blockingReasons[] = sprintf('Backend handoff package "%s" must be "%s".', $manifest->packageName(), ObjectPackageSurface::COMPOSER_PACKAGE);
        }
        $checks[] = 'objecting_package_name';

        $expectedNamespace = 'App\\'.$manifest->component();
        if ($manifest->namespace() !== $expectedNamespace) {
            $blockingReasons[] = sprintf('Backend handoff namespace "%s" must equal "%s".', $manifest->namespace(), $expectedNamespace);
        }
        $checks[] = 'backend_component_namespace';

        $expectedObjectingPathPrefix = sprintf('resources/objecting/%s/', $manifest->businessStem());
        if (!str_starts_with($manifest->adoptionManifestPath(), $expectedObjectingPathPrefix)) {
            $blockingReasons[] = sprintf('Backend handoff adoption manifest path "%s" must start with "%s".', $manifest->adoptionManifestPath(), $expectedObjectingPathPrefix);
        }
        if (!str_starts_with($manifest->readinessManifestPath(), $expectedObjectingPathPrefix)) {
            $blockingReasons[] = sprintf('Backend handoff readiness manifest path "%s" must start with "%s".', $manifest->readinessManifestPath(), $expectedObjectingPathPrefix);
        }
        $checks[] = 'backend_objecting_resource_paths';

        foreach (['composer dump-autoload', 'composer test:quality'] as $requiredGate) {
            if (!in_array($requiredGate, $manifest->qualityGates(), true)) {
                $blockingReasons[] = sprintf('Backend handoff quality gates must include "%s".', $requiredGate);
            }
        }
        $checks[] = 'backend_quality_gates';

        foreach (['test:quality', 'test:canon'] as $requiredScript) {
            if (!in_array($requiredScript, $manifest->requiredComposerScripts(), true)) {
                $blockingReasons[] = sprintf('Backend handoff required composer scripts must include "%s".', $requiredScript);
            }
        }
        $checks[] = 'backend_composer_scripts';

        if (null !== $manifest->exposingContractPath()) {
            $expectedContractPrefix = sprintf('contract/component/%s/%s/', $manifest->component(), $manifest->businessStem());
            if (!str_starts_with($manifest->exposingContractPath(), $expectedContractPrefix)) {
                $blockingReasons[] = sprintf('Exposing contract path "%s" must start with "%s".', $manifest->exposingContractPath(), $expectedContractPrefix);
            }
        }
        $checks[] = 'optional_exposing_contract_path';

        if (!$manifest->standaloneReady()) {
            $blockingReasons[] = 'Backend handoff manifest is not marked standalone-ready.';
        }
        $checks[] = 'standalone_ready';

        return new ObjectBackendHandoffReport(
            manifest: $manifest,
            checks: $checks,
            blockingReasons: array_values(array_unique($blockingReasons)),
        );
    }
}
