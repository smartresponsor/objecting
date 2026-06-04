<?php

declare(strict_types=1);

namespace App\Objecting\Reporter\Doctrine;

use App\Objecting\Contract\ObjectDoctrineMappingContract;
use App\Objecting\Report\ObjectDoctrineMappingReport;
use App\Objecting\ReporterInterface\Doctrine\ObjectDoctrineMappingContractReporterInterface;
use App\Objecting\ValueObject\ObjectFieldPackName;

final readonly class ObjectDoctrineMappingContractReporter implements ObjectDoctrineMappingContractReporterInterface
{
    public function report(ObjectDoctrineMappingContract $contract): ObjectDoctrineMappingReport
    {
        $checks = [];
        $blockingReasons = [];

        $expectedNamespace = 'App\\'.$contract->component();
        if ($contract->namespace() !== $expectedNamespace) {
            $blockingReasons[] = sprintf('Doctrine mapping namespace "%s" must equal "%s".', $contract->namespace(), $expectedNamespace);
        }
        $checks[] = 'backend_component_namespace';

        $expectedEntityClass = $contract->namespace().'\\Entity\\'.$contract->businessStem();
        if ($contract->entityClass() !== $expectedEntityClass) {
            $blockingReasons[] = sprintf('Doctrine mapping entity class "%s" must equal "%s".', $contract->entityClass(), $expectedEntityClass);
        }
        $checks[] = 'backend_entity_class';

        $expectedTablePrefix = $this->snake($contract->businessStem());
        if (!str_starts_with($contract->tableName(), $expectedTablePrefix)) {
            $blockingReasons[] = sprintf('Doctrine mapping table "%s" must start with entity prefix "%s".', $contract->tableName(), $expectedTablePrefix);
        }
        $checks[] = 'backend_table_prefix';

        $expectedObjectingPathPrefix = sprintf('resources/objecting/%s/', $contract->businessStem());
        if (!str_starts_with($contract->fieldPackContractPath(), $expectedObjectingPathPrefix)) {
            $blockingReasons[] = sprintf('Doctrine mapping field-pack contract path "%s" must start with "%s".', $contract->fieldPackContractPath(), $expectedObjectingPathPrefix);
        }
        $checks[] = 'backend_objecting_contract_path';

        foreach ([ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT, ObjectFieldPackName::TITLE] as $requiredBaselinePack) {
            if (!in_array($requiredBaselinePack, $contract->requiredFieldPacks(), true)) {
                $blockingReasons[] = sprintf('Doctrine mapping required field packs must include "%s".', $requiredBaselinePack);
            }
        }
        $checks[] = 'required_baseline_field_packs';

        foreach ($contract->embeddableClasses() as $embeddableClass) {
            if (!str_starts_with($embeddableClass, 'App\\Objecting\\Embeddable\\Object') || !str_ends_with($embeddableClass, 'Embeddable')) {
                $blockingReasons[] = sprintf('Doctrine mapping embeddable class "%s" must be an Objecting embeddable.', $embeddableClass);
            }
        }
        $checks[] = 'objecting_embeddable_classes';

        foreach ($contract->embeddedTraitClasses() as $traitClass) {
            if (!str_starts_with($traitClass, 'App\\Objecting\\EntityTrait\\Embeddable\\Object') || !str_ends_with($traitClass, 'EmbeddableTrait')) {
                $blockingReasons[] = sprintf('Doctrine mapping embedded trait class "%s" must be an Objecting embeddable trait.', $traitClass);
            }
        }
        $checks[] = 'objecting_embeddable_traits';

        foreach ($contract->columnNames() as $columnName) {
            if (!preg_match('/^[a-z][a-z0-9_]*$/', $columnName)) {
                $blockingReasons[] = sprintf('Doctrine mapping column "%s" must be snake_case.', $columnName);
            }
            if ($contract->objectColumnsPrefixed() && !str_starts_with($columnName, 'object_')) {
                $blockingReasons[] = sprintf('Doctrine mapping Objecting column "%s" must start with object_.', $columnName);
            }
        }
        $checks[] = 'object_column_names';

        if (!$contract->columnPrefixFalse()) {
            $blockingReasons[] = 'Doctrine mapping contract must use columnPrefix=false because Objecting embeddables already own object_* column names.';
        }
        $checks[] = 'objecting_column_prefix_policy';

        if (!$contract->backendOwnsMigrations()) {
            $blockingReasons[] = 'Doctrine mapping contract must keep backend components as migration owners.';
        }
        $checks[] = 'backend_migration_ownership';

        return new ObjectDoctrineMappingReport(
            contract: $contract,
            checks: $checks,
            blockingReasons: array_values(array_unique($blockingReasons)),
        );
    }

    private function snake(string $value): string
    {
        $snake = strtolower((string) preg_replace('/(?<!^)[A-Z]/', '_$0', $value));

        return '' === $snake ? strtolower($value) : $snake;
    }
}
