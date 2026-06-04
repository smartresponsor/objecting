<?php

declare(strict_types=1);

namespace App\Objecting\Contract;

final readonly class ObjectSchemaMirrorContract
{
    /**
     * @param list<string> $requiredFieldPacks
     * @param list<string> $objectColumnNames
     * @param list<string> $backendColumnNames
     */
    public function __construct(
        private string $component,
        private string $businessStem,
        private string $namespace,
        private string $entityClass,
        private string $tableName,
        private string $fieldPackContractPath,
        private string $doctrineMappingContractPath,
        private string $backendSchemaMirrorPath,
        private string $exposingSchemaMirrorPath,
        private array $requiredFieldPacks,
        private array $objectColumnNames,
        private array $backendColumnNames,
        private bool $backendOwnsMigrations = true,
        private bool $exposingOwnsMirror = true,
        private bool $objectingOwnsSystemColumns = true,
        private bool $schemaMirrorInformational = true,
    ) {
        foreach ([
            'component' => $this->component,
            'business stem' => $this->businessStem,
            'namespace' => $this->namespace,
            'entity class' => $this->entityClass,
            'table name' => $this->tableName,
            'field-pack contract path' => $this->fieldPackContractPath,
            'Doctrine mapping contract path' => $this->doctrineMappingContractPath,
            'backend schema mirror path' => $this->backendSchemaMirrorPath,
            'Exposing schema mirror path' => $this->exposingSchemaMirrorPath,
        ] as $label => $value) {
            if ('' === $value) {
                throw new \InvalidArgumentException(sprintf('Objecting schema mirror contract %s cannot be empty.', $label));
            }
        }

        foreach (['component' => $this->component, 'business stem' => $this->businessStem] as $label => $value) {
            if (1 !== preg_match('/^[A-Z][A-Za-z0-9]*$/', $value)) {
                throw new \InvalidArgumentException(sprintf('Objecting schema mirror contract %s must be PascalCase.', $label));
            }
        }

        if (!str_starts_with($this->namespace, 'App\\')) {
            throw new \InvalidArgumentException('Objecting schema mirror contract namespace must start with App\\.');
        }

        if (!str_starts_with($this->entityClass, $this->namespace.'\\Entity\\')) {
            throw new \InvalidArgumentException('Objecting schema mirror contract entity class must live under the backend Entity namespace.');
        }

        foreach ([
            'field-pack contract path' => $this->fieldPackContractPath,
            'Doctrine mapping contract path' => $this->doctrineMappingContractPath,
            'backend schema mirror path' => $this->backendSchemaMirrorPath,
            'Exposing schema mirror path' => $this->exposingSchemaMirrorPath,
        ] as $label => $path) {
            $this->assertRelativePath($path, $label);
        }

        foreach ([
            'required field packs' => $this->requiredFieldPacks,
            'object column names' => $this->objectColumnNames,
            'backend column names' => $this->backendColumnNames,
        ] as $label => $values) {
            if ([] === $values) {
                throw new \InvalidArgumentException(sprintf('Objecting schema mirror contract %s cannot be empty.', $label));
            }
            if (array_values(array_unique($values)) !== $values) {
                throw new \InvalidArgumentException(sprintf('Objecting schema mirror contract has duplicate %s.', $label));
            }
            foreach ($values as $value) {
                if ('' === $value) {
                    throw new \InvalidArgumentException(sprintf('Objecting schema mirror contract %s cannot contain empty entries.', $label));
                }
            }
        }

        foreach ($this->requiredFieldPacks as $fieldPack) {
            if (!ObjectFieldPackName::isKnown($fieldPack)) {
                throw new \InvalidArgumentException(sprintf('Unknown Objecting field pack "%s" in schema mirror contract.', $fieldPack));
            }
        }
    }

    public function component(): string
    {
        return $this->component;
    }

    public function businessStem(): string
    {
        return $this->businessStem;
    }

    public function namespace(): string
    {
        return $this->namespace;
    }

    public function entityClass(): string
    {
        return $this->entityClass;
    }

    public function tableName(): string
    {
        return $this->tableName;
    }

    public function fieldPackContractPath(): string
    {
        return $this->fieldPackContractPath;
    }

    public function doctrineMappingContractPath(): string
    {
        return $this->doctrineMappingContractPath;
    }

    public function backendSchemaMirrorPath(): string
    {
        return $this->backendSchemaMirrorPath;
    }

    public function exposingSchemaMirrorPath(): string
    {
        return $this->exposingSchemaMirrorPath;
    }

    /** @return list<string> */
    public function requiredFieldPacks(): array
    {
        return $this->requiredFieldPacks;
    }

    /** @return list<string> */
    public function objectColumnNames(): array
    {
        return $this->objectColumnNames;
    }

    /** @return list<string> */
    public function backendColumnNames(): array
    {
        return $this->backendColumnNames;
    }

    public function backendOwnsMigrations(): bool
    {
        return $this->backendOwnsMigrations;
    }

    public function exposingOwnsMirror(): bool
    {
        return $this->exposingOwnsMirror;
    }

    public function objectingOwnsSystemColumns(): bool
    {
        return $this->objectingOwnsSystemColumns;
    }

    public function schemaMirrorInformational(): bool
    {
        return $this->schemaMirrorInformational;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'component' => $this->component,
            'business_stem' => $this->businessStem,
            'namespace' => $this->namespace,
            'entity_class' => $this->entityClass,
            'table_name' => $this->tableName,
            'field_pack_contract_path' => $this->fieldPackContractPath,
            'doctrine_mapping_contract_path' => $this->doctrineMappingContractPath,
            'backend_schema_mirror_path' => $this->backendSchemaMirrorPath,
            'exposing_schema_mirror_path' => $this->exposingSchemaMirrorPath,
            'required_field_packs' => $this->requiredFieldPacks,
            'object_column_names' => $this->objectColumnNames,
            'backend_column_names' => $this->backendColumnNames,
            'backend_owns_migrations' => $this->backendOwnsMigrations,
            'exposing_owns_mirror' => $this->exposingOwnsMirror,
            'objecting_owns_system_columns' => $this->objectingOwnsSystemColumns,
            'schema_mirror_informational' => $this->schemaMirrorInformational,
        ];
    }

    private function assertRelativePath(string $path, string $label): void
    {
        if ('' === $path || str_starts_with($path, '/') || str_starts_with($path, '\\') || str_contains($path, '..')) {
            throw new \InvalidArgumentException(sprintf('Objecting schema mirror contract %s must be a safe relative path.', $label));
        }
    }
}
