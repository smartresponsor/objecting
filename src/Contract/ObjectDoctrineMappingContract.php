<?php

declare(strict_types=1);

namespace App\Objecting\Contract;

final readonly class ObjectDoctrineMappingContract
{
    /**
     * @param list<string> $requiredFieldPacks
     * @param list<string> $embeddableClasses
     * @param list<string> $embeddedTraitClasses
     * @param list<string> $columnNames
     */
    public function __construct(
        private string $component,
        private string $businessStem,
        private string $namespace,
        private string $entityClass,
        private string $tableName,
        private string $fieldPackContractPath,
        private array $requiredFieldPacks,
        private array $embeddableClasses,
        private array $embeddedTraitClasses,
        private array $columnNames,
        private bool $columnPrefixFalse = true,
        private bool $backendOwnsMigrations = true,
        private bool $objectColumnsPrefixed = true,
    ) {
        foreach ([
            'component' => $this->component,
            'business stem' => $this->businessStem,
            'namespace' => $this->namespace,
            'entity class' => $this->entityClass,
            'table nameEntity' => $this->tableName,
            'field-pack contract path' => $this->fieldPackContractPath,
        ] as $label => $value) {
            if ('' === $value) {
                throw new \InvalidArgumentException(sprintf('Objecting Doctrine mapping contract %s cannot be empty.', $label));
            }
        }

        foreach (['component' => $this->component, 'business stem' => $this->businessStem] as $label => $value) {
            if (1 !== preg_match('/^[A-Z][A-Za-z0-9]*$/', $value)) {
                throw new \InvalidArgumentException(sprintf('Objecting Doctrine mapping contract %s must be PascalCase.', $label));
            }
        }

        if (!str_starts_with($this->namespace, 'App\\')) {
            throw new \InvalidArgumentException('Objecting Doctrine mapping contract namespace must start with App\\.');
        }

        if (!str_starts_with($this->entityClass, $this->namespace.'\\Entity\\')) {
            throw new \InvalidArgumentException('Objecting Doctrine mapping contract entity class must live under the backend Entity namespace.');
        }

        $this->assertRelativePath($this->fieldPackContractPath, 'field-pack contract path');

        foreach ([
            'required field packs' => $this->requiredFieldPacks,
            'embeddable classes' => $this->embeddableClasses,
            'embedded trait classes' => $this->embeddedTraitClasses,
            'column names' => $this->columnNames,
        ] as $label => $values) {
            if ([] === $values) {
                throw new \InvalidArgumentException(sprintf('Objecting Doctrine mapping contract %s cannot be empty.', $label));
            }
            if (array_values(array_unique($values)) !== $values) {
                throw new \InvalidArgumentException(sprintf('Objecting Doctrine mapping contract has duplicate %s.', $label));
            }
            foreach ($values as $value) {
                if ('' === $value) {
                    throw new \InvalidArgumentException(sprintf('Objecting Doctrine mapping contract %s cannot contain empty entries.', $label));
                }
            }
        }

        foreach ($this->requiredFieldPacks as $fieldPack) {
            if (!ObjectFieldPackName::isKnown($fieldPack)) {
                throw new \InvalidArgumentException(sprintf('Unknown Objecting field pack "%s" in Doctrine mapping contract.', $fieldPack));
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

    /** @return list<string> */
    public function requiredFieldPacks(): array
    {
        return $this->requiredFieldPacks;
    }

    /** @return list<string> */
    public function embeddableClasses(): array
    {
        return $this->embeddableClasses;
    }

    /** @return list<string> */
    public function embeddedTraitClasses(): array
    {
        return $this->embeddedTraitClasses;
    }

    /** @return list<string> */
    public function columnNames(): array
    {
        return $this->columnNames;
    }

    public function columnPrefixFalse(): bool
    {
        return $this->columnPrefixFalse;
    }

    public function backendOwnsMigrations(): bool
    {
        return $this->backendOwnsMigrations;
    }

    public function objectColumnsPrefixed(): bool
    {
        return $this->objectColumnsPrefixed;
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
            'required_field_packs' => $this->requiredFieldPacks,
            'embeddable_classes' => $this->embeddableClasses,
            'embedded_trait_classes' => $this->embeddedTraitClasses,
            'column_names' => $this->columnNames,
            'column_prefix_false' => $this->columnPrefixFalse,
            'backend_owns_migrations' => $this->backendOwnsMigrations,
            'object_columns_prefixed' => $this->objectColumnsPrefixed,
        ];
    }

    private function assertRelativePath(string $path, string $label): void
    {
        if ('' === $path || str_starts_with($path, '/') || str_starts_with($path, '\\') || str_contains($path, '..')) {
            throw new \InvalidArgumentException(sprintf('Objecting Doctrine mapping contract %s must be a safe relative path.', $label));
        }
    }
}
