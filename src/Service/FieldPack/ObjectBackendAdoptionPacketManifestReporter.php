<?php

declare(strict_types=1);

namespace App\Objecting\Service\FieldPack;

use App\Objecting\ServiceInterface\FieldPack\ObjectBackendAdoptionPacketManifestReporterInterface;
use App\Objecting\ValueObject\ObjectBackendAdoptionPacketManifest;
use App\Objecting\ValueObject\ObjectBackendAdoptionPacketReport;
use App\Objecting\ValueObject\ObjectFieldPackName;
use App\Objecting\ValueObject\ObjectPackageSurface;

final readonly class ObjectBackendAdoptionPacketManifestReporter implements ObjectBackendAdoptionPacketManifestReporterInterface
{
    public function report(ObjectBackendAdoptionPacketManifest $manifest): ObjectBackendAdoptionPacketReport
    {
        $checks = [];
        $blockingReasons = [];

        if (ObjectPackageSurface::COMPOSER_PACKAGE !== $manifest->packageName()) {
            $blockingReasons[] = sprintf('Backend adoption packet package "%s" must be "%s".', $manifest->packageName(), ObjectPackageSurface::COMPOSER_PACKAGE);
        }
        $checks[] = 'objecting_package_name';

        $expectedNamespace = 'App\\'.$manifest->component();
        if ($manifest->namespace() !== $expectedNamespace) {
            $blockingReasons[] = sprintf('Backend adoption packet namespace "%s" must equal "%s".', $manifest->namespace(), $expectedNamespace);
        }
        $checks[] = 'backend_component_namespace';

        $expectedObjectingPathPrefix = sprintf('resources/objecting/%s/', $manifest->businessStem());
        foreach ([
            'field-pack contract path' => $manifest->fieldPackContractPath(),
            'readiness manifest path' => $manifest->readinessManifestPath(),
            'adoption manifest path' => $manifest->adoptionManifestPath(),
            'handoff manifest path' => $manifest->handoffManifestPath(),
        ] as $label => $path) {
            if (!str_starts_with($path, $expectedObjectingPathPrefix)) {
                $blockingReasons[] = sprintf('Backend adoption packet %s "%s" must start with "%s".', $label, $path, $expectedObjectingPathPrefix);
            }
        }
        $checks[] = 'backend_objecting_resource_paths';

        foreach ([ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT, ObjectFieldPackName::TITLE] as $requiredBaselinePack) {
            if (!in_array($requiredBaselinePack, $manifest->requiredFieldPacks(), true)) {
                $blockingReasons[] = sprintf('Backend adoption packet required field packs must include "%s".', $requiredBaselinePack);
            }
        }
        $checks[] = 'required_baseline_field_packs';

        if (null !== $manifest->titleAliasProfile() && !in_array(ObjectFieldPackName::TITLE, $manifest->requiredFieldPacks(), true)) {
            $blockingReasons[] = 'Backend adoption packet declares a title-alias profile without object_title in required field packs.';
        }
        $checks[] = 'title_alias_requires_object_title';

        foreach (['composer dump-autoload', 'composer test:quality'] as $requiredGate) {
            if (!in_array($requiredGate, $manifest->qualityGates(), true)) {
                $blockingReasons[] = sprintf('Backend adoption packet quality gates must include "%s".', $requiredGate);
            }
        }
        $checks[] = 'backend_quality_gates';

        foreach (['test:quality', 'test:canon'] as $requiredScript) {
            if (!in_array($requiredScript, $manifest->requiredComposerScripts(), true)) {
                $blockingReasons[] = sprintf('Backend adoption packet required composer scripts must include "%s".', $requiredScript);
            }
        }
        $checks[] = 'backend_composer_scripts';

        foreach ([
            $manifest->fieldPackContractPath(),
            $manifest->readinessManifestPath(),
            $manifest->adoptionManifestPath(),
            $manifest->handoffManifestPath(),
            $manifest->releaseClosureManifestPath(),
        ] as $requiredArtifact) {
            if (!in_array($requiredArtifact, $manifest->packetArtifacts(), true)) {
                $blockingReasons[] = sprintf('Backend adoption packet artifacts must include "%s".', $requiredArtifact);
            }
        }
        $checks[] = 'backend_packet_artifacts';

        if (ObjectPackageSurface::RELEASE_CLOSURE_EXAMPLE !== $manifest->releaseClosureManifestPath()) {
            $blockingReasons[] = sprintf('Backend adoption packet release closure manifest path "%s" must be "%s".', $manifest->releaseClosureManifestPath(), ObjectPackageSurface::RELEASE_CLOSURE_EXAMPLE);
        }
        $checks[] = 'objecting_release_closure_link';

        if (null !== $manifest->exposingContractPath()) {
            $expectedContractPrefix = sprintf('contract/component/%s/%s/', $manifest->component(), $manifest->businessStem());
            if (!str_starts_with($manifest->exposingContractPath(), $expectedContractPrefix)) {
                $blockingReasons[] = sprintf('Exposing contract path "%s" must start with "%s".', $manifest->exposingContractPath(), $expectedContractPrefix);
            }
        }
        $checks[] = 'optional_exposing_contract_path';

        if (!$manifest->standaloneReady()) {
            $blockingReasons[] = 'Backend adoption packet is not marked standalone-ready.';
        }
        $checks[] = 'standalone_ready';

        return new ObjectBackendAdoptionPacketReport(
            manifest: $manifest,
            checks: $checks,
            blockingReasons: array_values(array_unique($blockingReasons)),
        );
    }
}
