<?php

declare(strict_types=1);

namespace App\Objecting\Service\FieldPack;

use App\Objecting\ServiceInterface\FieldPack\ObjectBackendImportContractReporterInterface;
use App\Objecting\ValueObject\ObjectBackendImportContract;
use App\Objecting\ValueObject\ObjectBackendImportReport;
use App\Objecting\ValueObject\ObjectFieldPackName;
use App\Objecting\ValueObject\ObjectPackageSurface;

final readonly class ObjectBackendImportContractReporter implements ObjectBackendImportContractReporterInterface
{
    public function report(ObjectBackendImportContract $contract): ObjectBackendImportReport
    {
        $checks = [];
        $blockingReasons = [];

        if (ObjectPackageSurface::COMPOSER_PACKAGE !== $contract->packageName()) {
            $blockingReasons[] = sprintf('Backend import package "%s" must be "%s".', $contract->packageName(), ObjectPackageSurface::COMPOSER_PACKAGE);
        }
        $checks[] = 'objecting_package_name';

        $expectedNamespace = 'App\\'.$contract->component();
        if ($contract->namespace() !== $expectedNamespace) {
            $blockingReasons[] = sprintf('Backend import namespace "%s" must equal "%s".', $contract->namespace(), $expectedNamespace);
        }
        $checks[] = 'backend_component_namespace';

        $expectedEntityClass = $contract->namespace().'\\Entity\\'.$contract->businessStem();
        if ($contract->entityClass() !== $expectedEntityClass) {
            $blockingReasons[] = sprintf('Backend import entity class "%s" must equal "%s".', $contract->entityClass(), $expectedEntityClass);
        }
        $checks[] = 'backend_entity_class';

        $expectedObjectingPrefix = sprintf('resources/objecting/%s/', $contract->businessStem());
        foreach ([
            'adoption packet path' => $contract->adoptionPacketPath(),
            'field-pack contract path' => $contract->fieldPackContractPath(),
            'Doctrine mapping contract path' => $contract->doctrineMappingContractPath(),
            'Exposing bridge contract path' => $contract->exposingBridgeContractPath(),
        ] as $label => $path) {
            if (!str_starts_with($path, $expectedObjectingPrefix)) {
                $blockingReasons[] = sprintf('Backend import %s "%s" must start with "%s".', $label, $path, $expectedObjectingPrefix);
            }
        }
        $checks[] = 'backend_objecting_import_paths';

        $expectedSchemaPrefix = sprintf('resources/schema/%s/', $contract->businessStem());
        if (!str_starts_with($contract->schemaMirrorContractPath(), $expectedSchemaPrefix)) {
            $blockingReasons[] = sprintf('Backend import schema mirror path "%s" must start with "%s".', $contract->schemaMirrorContractPath(), $expectedSchemaPrefix);
        }
        $checks[] = 'backend_schema_mirror_path';

        foreach ([ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT, ObjectFieldPackName::TITLE] as $requiredBaselinePack) {
            if (!in_array($requiredBaselinePack, $contract->requiredFieldPacks(), true)) {
                $blockingReasons[] = sprintf('Backend import required field packs must include "%s".', $requiredBaselinePack);
            }
        }
        $checks[] = 'required_baseline_field_packs';

        if (null !== $contract->titleAliasProfile() && !in_array(ObjectFieldPackName::TITLE, $contract->requiredFieldPacks(), true)) {
            $blockingReasons[] = 'Backend import declares a title-alias profile without object_title in required field packs.';
        }
        $checks[] = 'title_alias_requires_object_title';

        foreach (['composer dump-autoload', 'composer test:quality', 'php tools/test/objecting_backend_import_contract_check.php'] as $requiredGate) {
            if (!in_array($requiredGate, $contract->qualityGates(), true)) {
                $blockingReasons[] = sprintf('Backend import quality gates must include "%s".', $requiredGate);
            }
        }
        $checks[] = 'backend_import_quality_gates';

        foreach (['test:quality', 'test:canon', 'test:backend-import'] as $requiredScript) {
            if (!in_array($requiredScript, $contract->requiredComposerScripts(), true)) {
                $blockingReasons[] = sprintf('Backend import required composer scripts must include "%s".', $requiredScript);
            }
        }
        $checks[] = 'backend_import_composer_scripts';

        foreach ([
            $contract->adoptionPacketPath(),
            $contract->fieldPackContractPath(),
            $contract->doctrineMappingContractPath(),
            $contract->schemaMirrorContractPath(),
            $contract->exposingBridgeContractPath(),
        ] as $requiredArtifact) {
            if (!in_array($requiredArtifact, $contract->importArtifacts(), true)) {
                $blockingReasons[] = sprintf('Backend import artifacts must include "%s".', $requiredArtifact);
            }
        }
        $checks[] = 'backend_import_artifacts';

        if (!$contract->backendOwnsRuntime()) {
            $blockingReasons[] = 'Backend import must keep backend components as runtime owners.';
        }
        $checks[] = 'backend_runtime_owner';
        if (!$contract->objectingOwnsSystemFields()) {
            $blockingReasons[] = 'Backend import must keep Objecting as system-field owner.';
        }
        $checks[] = 'objecting_system_field_owner';
        if (!$contract->importInformational()) {
            $blockingReasons[] = 'Backend import contract must stay informational and must not replace backend runtime files.';
        }
        $checks[] = 'backend_import_informational_only';

        return new ObjectBackendImportReport($contract, $checks, array_values(array_unique($blockingReasons)));
    }
}
