<?php

declare(strict_types=1);

namespace App\Objecting\Service\Schema;

use App\Objecting\ServiceInterface\Schema\ObjectSchemaMirrorContractReporterInterface;
use App\Objecting\ValueObject\ObjectFieldPackName;
use App\Objecting\ValueObject\ObjectSchemaMirrorContract;
use App\Objecting\ValueObject\ObjectSchemaMirrorReport;

final readonly class ObjectSchemaMirrorContractReporter implements ObjectSchemaMirrorContractReporterInterface
{
    public function report(ObjectSchemaMirrorContract $contract): ObjectSchemaMirrorReport
    {
        $checks = [];
        $blockingReasons = [];

        $expectedNamespace = 'App\\'.$contract->component();
        if ($contract->namespace() !== $expectedNamespace) {
            $blockingReasons[] = sprintf('Schema mirror namespace "%s" must equal "%s".', $contract->namespace(), $expectedNamespace);
        }
        $checks[] = 'schema_mirror_backend_namespace';

        $expectedEntityClass = $contract->namespace().'\\Entity\\'.$contract->businessStem();
        if ($contract->entityClass() !== $expectedEntityClass) {
            $blockingReasons[] = sprintf('Schema mirror entity class "%s" must equal "%s".', $contract->entityClass(), $expectedEntityClass);
        }
        $checks[] = 'schema_mirror_entity_class';

        $expectedTablePrefix = $this->snake($contract->businessStem());
        if (!str_starts_with($contract->tableName(), $expectedTablePrefix)) {
            $blockingReasons[] = sprintf('Schema mirror table "%s" must start with entity prefix "%s".', $contract->tableName(), $expectedTablePrefix);
        }
        $checks[] = 'schema_mirror_table_prefix';

        $expectedObjectingPathPrefix = sprintf('resources/objecting/%s/', $contract->businessStem());
        foreach ([
            'field-pack contract path' => $contract->fieldPackContractPath(),
            'Doctrine mapping contract path' => $contract->doctrineMappingContractPath(),
        ] as $label => $path) {
            if (!str_starts_with($path, $expectedObjectingPathPrefix)) {
                $blockingReasons[] = sprintf('Schema mirror %s "%s" must start with "%s".', $label, $path, $expectedObjectingPathPrefix);
            }
        }
        $checks[] = 'schema_mirror_objecting_contract_paths';

        $expectedBackendSchemaPrefix = sprintf('resources/schema/%s/', $contract->businessStem());
        if (!str_starts_with($contract->backendSchemaMirrorPath(), $expectedBackendSchemaPrefix)) {
            $blockingReasons[] = sprintf('Backend schema mirror path "%s" must start with "%s".', $contract->backendSchemaMirrorPath(), $expectedBackendSchemaPrefix);
        }
        $checks[] = 'backend_schema_mirror_path';

        $expectedExposingPrefix = sprintf('contract/component/%s/%s/', $contract->component(), $contract->businessStem());
        if (!str_starts_with($contract->exposingSchemaMirrorPath(), $expectedExposingPrefix)) {
            $blockingReasons[] = sprintf('Exposing schema mirror path "%s" must start with "%s".', $contract->exposingSchemaMirrorPath(), $expectedExposingPrefix);
        }
        $checks[] = 'exposing_schema_mirror_path';

        foreach ([ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT, ObjectFieldPackName::TITLE] as $requiredBaselinePack) {
            if (!in_array($requiredBaselinePack, $contract->requiredFieldPacks(), true)) {
                $blockingReasons[] = sprintf('Schema mirror required field packs must include "%s".', $requiredBaselinePack);
            }
        }
        $checks[] = 'schema_mirror_baseline_field_packs';

        foreach ($contract->objectColumnNames() as $columnName) {
            if (!$this->isSnakeCase($columnName)) {
                $blockingReasons[] = sprintf('Schema mirror Objecting column "%s" must be snake_case.', $columnName);
            }
            if (!str_starts_with($columnName, 'object_')) {
                $blockingReasons[] = sprintf('Schema mirror Objecting column "%s" must start with object_.', $columnName);
            }
        }
        $checks[] = 'schema_mirror_object_columns';

        foreach ($contract->backendColumnNames() as $columnName) {
            if (!$this->isSnakeCase($columnName)) {
                $blockingReasons[] = sprintf('Schema mirror backend column "%s" must be snake_case.', $columnName);
            }
            if (str_starts_with($columnName, 'object_')) {
                $blockingReasons[] = sprintf('Schema mirror backend business column "%s" must not use object_ prefix.', $columnName);
            }
        }
        $checks[] = 'schema_mirror_backend_columns';

        if (!$contract->backendOwnsMigrations()) {
            $blockingReasons[] = 'Schema mirror contract must keep backend components as Doctrine migration owners.';
        }
        $checks[] = 'schema_mirror_backend_migration_ownership';

        if (!$contract->exposingOwnsMirror()) {
            $blockingReasons[] = 'Schema mirror contract must keep Exposing as the API/schema mirror owner.';
        }
        $checks[] = 'schema_mirror_exposing_ownership';

        if (!$contract->objectingOwnsSystemColumns()) {
            $blockingReasons[] = 'Schema mirror contract must keep Objecting as the object_* system column owner.';
        }
        $checks[] = 'schema_mirror_objecting_system_column_ownership';

        if (!$contract->schemaMirrorInformational()) {
            $blockingReasons[] = 'Schema mirror contract must stay informational and must not replace Doctrine migrations.';
        }
        $checks[] = 'schema_mirror_informational_only';

        return new ObjectSchemaMirrorReport(
            contract: $contract,
            checks: $checks,
            blockingReasons: array_values(array_unique($blockingReasons)),
        );
    }

    private function isSnakeCase(string $value): bool
    {
        return 1 === preg_match('/^[a-z][a-z0-9_]*$/', $value);
    }

    private function snake(string $value): string
    {
        $snake = strtolower((string) preg_replace('/(?<!^)[A-Z]/', '_$0', $value));

        return '' === $snake ? strtolower($value) : $snake;
    }
}
