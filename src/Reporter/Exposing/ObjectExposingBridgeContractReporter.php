<?php

declare(strict_types=1);

namespace App\Objecting\Reporter\Exposing;

use App\Objecting\Contract\ObjectExposingBridgeContract;
use App\Objecting\Report\ObjectExposingBridgeReport;
use App\Objecting\ReporterInterface\Exposing\ObjectExposingBridgeContractReporterInterface;
use App\Objecting\ValueObject\ObjectFieldPackName;

final readonly class ObjectExposingBridgeContractReporter implements ObjectExposingBridgeContractReporterInterface
{
    public function report(ObjectExposingBridgeContract $contract): ObjectExposingBridgeReport
    {
        $checks = [];
        $blockingReasons = [];

        $expectedNamespace = 'App\\'.$contract->component();
        if ($contract->namespace() !== $expectedNamespace) {
            $blockingReasons[] = sprintf('Exposing bridge namespace "%s" must equal "%s".', $contract->namespace(), $expectedNamespace);
        }
        $checks[] = 'exposing_bridge_backend_namespace';

        $expectedEntityClass = $contract->namespace().'\\Entity\\'.$contract->businessStem();
        if ($contract->entityClass() !== $expectedEntityClass) {
            $blockingReasons[] = sprintf('Exposing bridge entity class "%s" must equal "%s".', $contract->entityClass(), $expectedEntityClass);
        }
        $checks[] = 'exposing_bridge_entity_class';

        $expectedObjectingPrefix = sprintf('resources/objecting/%s/', $contract->businessStem());
        foreach ([
            'field-pack contract path' => $contract->fieldPackContractPath(),
            'Doctrine mapping contract path' => $contract->doctrineMappingContractPath(),
            'backend adoption packet path' => $contract->backendAdoptionPacketPath(),
        ] as $label => $path) {
            if (!str_starts_with($path, $expectedObjectingPrefix)) {
                $blockingReasons[] = sprintf('Exposing bridge %s "%s" must start with "%s".', $label, $path, $expectedObjectingPrefix);
            }
        }
        $checks[] = 'exposing_bridge_objecting_contract_paths';

        $expectedSchemaMirrorPrefix = sprintf('resources/schema/%s/', $contract->businessStem());
        if (!str_starts_with($contract->schemaMirrorContractPath(), $expectedSchemaMirrorPrefix)) {
            $blockingReasons[] = sprintf('Exposing bridge schema mirror contract path "%s" must start with "%s".', $contract->schemaMirrorContractPath(), $expectedSchemaMirrorPrefix);
        }
        $checks[] = 'exposing_bridge_backend_schema_mirror_path';

        $expectedExposingPrefix = sprintf('contract/component/%s/%s/', $contract->component(), $contract->businessStem());
        foreach (['OpenAPI path' => $contract->exposingOpenApiPath(), 'schema mirror path' => $contract->exposingSchemaMirrorPath()] as $label => $path) {
            if (!str_starts_with($path, $expectedExposingPrefix)) {
                $blockingReasons[] = sprintf('Exposing bridge %s "%s" must start with "%s".', $label, $path, $expectedExposingPrefix);
            }
        }
        $checks[] = 'exposing_bridge_exposing_paths';

        if (!str_starts_with($contract->openApiSchemaName(), $contract->businessStem())) {
            $blockingReasons[] = sprintf('Exposing bridge OpenAPI schema "%s" must start with business stem "%s".', $contract->openApiSchemaName(), $contract->businessStem());
        }
        $checks[] = 'exposing_bridge_openapi_schema_name';

        if (!str_starts_with($contract->titleAliasProfile(), 'object_title_')) {
            $blockingReasons[] = sprintf('Exposing bridge title alias profile "%s" must be an object_title_* profile.', $contract->titleAliasProfile());
        }
        $checks[] = 'exposing_bridge_title_alias_profile';

        foreach ([ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT, ObjectFieldPackName::TITLE] as $requiredBaselinePack) {
            if (!in_array($requiredBaselinePack, $contract->requiredFieldPacks(), true)) {
                $blockingReasons[] = sprintf('Exposing bridge required field packs must include "%s".', $requiredBaselinePack);
            }
        }
        $checks[] = 'exposing_bridge_baseline_field_packs';

        foreach ([$contract->fieldPackContractPath(), $contract->doctrineMappingContractPath(), $contract->schemaMirrorContractPath(), $contract->backendAdoptionPacketPath()] as $requiredArtifact) {
            if (!in_array($requiredArtifact, $contract->exportArtifacts(), true)) {
                $blockingReasons[] = sprintf('Exposing bridge export artifacts must include "%s".', $requiredArtifact);
            }
        }
        $checks[] = 'exposing_bridge_export_artifacts';

        if (!$contract->backendOwnsRuntime()) {
            $blockingReasons[] = 'Exposing bridge must keep backend components as runtime owners.';
        }
        $checks[] = 'exposing_bridge_backend_runtime_owner';
        if (!$contract->objectingOwnsFieldPacks()) {
            $blockingReasons[] = 'Exposing bridge must keep Objecting as field-pack owner.';
        }
        $checks[] = 'exposing_bridge_objecting_field_pack_owner';
        if (!$contract->exposingOwnsApiContract()) {
            $blockingReasons[] = 'Exposing bridge must keep Exposing as API contract owner.';
        }
        $checks[] = 'exposing_bridge_exposing_api_contract_owner';
        if (!$contract->bridgeInformational()) {
            $blockingReasons[] = 'Exposing bridge must stay informational and must not replace backend runtime or Exposing contracts.';
        }
        $checks[] = 'exposing_bridge_informational_only';

        return new ObjectExposingBridgeReport($contract, $checks, array_values(array_unique($blockingReasons)));
    }
}
