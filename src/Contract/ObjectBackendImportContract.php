<?php

declare(strict_types=1);

namespace App\Objecting\Contract;

use App\Objecting\ValueObject\ObjectFieldPackName;
use App\Objecting\ValueObject\ObjectTitleAliasProfileName;

final readonly class ObjectBackendImportContract
{
    /**
     * @param list<string> $requiredFieldPacks
     * @param list<string> $importArtifacts
     * @param list<string> $qualityGates
     * @param list<string> $requiredComposerScripts
     */
    public function __construct(
        private string $component,
        private string $businessStem,
        private string $namespace,
        private string $entityClass,
        private string $backendProjectRoot,
        private string $packageName,
        private string $packageConstraint,
        private string $adoptionPacketPath,
        private string $fieldPackContractPath,
        private string $doctrineMappingContractPath,
        private string $schemaMirrorContractPath,
        private string $exposingBridgeContractPath,
        private array $requiredFieldPacks,
        private array $importArtifacts,
        private array $qualityGates,
        private array $requiredComposerScripts,
        private ?string $titleAliasProfile = null,
        private bool $backendOwnsRuntime = true,
        private bool $objectingOwnsSystemFields = true,
        private bool $importInformational = true,
    ) {
        foreach ([
            'component' => $this->component,
            'business stem' => $this->businessStem,
            'namespace' => $this->namespace,
            'entity class' => $this->entityClass,
            'backend project root' => $this->backendProjectRoot,
            'package name' => $this->packageName,
            'package constraint' => $this->packageConstraint,
            'adoption packet path' => $this->adoptionPacketPath,
            'field-pack contract path' => $this->fieldPackContractPath,
            'Doctrine mapping contract path' => $this->doctrineMappingContractPath,
            'schema mirror contract path' => $this->schemaMirrorContractPath,
            'Exposing bridge contract path' => $this->exposingBridgeContractPath,
        ] as $label => $value) {
            if ('' === $value) {
                throw new \InvalidArgumentException(sprintf('Objecting backend import contract %s cannot be empty.', $label));
            }
        }

        foreach (['component' => $this->component, 'business stem' => $this->businessStem] as $label => $value) {
            if (1 !== preg_match('/^[A-Z][A-Za-z0-9]*$/', $value)) {
                throw new \InvalidArgumentException(sprintf('Objecting backend import contract %s must be PascalCase.', $label));
            }
        }

        if (!str_starts_with($this->namespace, 'App\\')) {
            throw new \InvalidArgumentException('Objecting backend import contract namespace must start with App\\.');
        }

        if (!str_starts_with($this->entityClass, $this->namespace.'\\Entity\\')) {
            throw new \InvalidArgumentException('Objecting backend import contract entity class must live under the backend Entity namespace.');
        }

        foreach ([
            'adoption packet path' => $this->adoptionPacketPath,
            'field-pack contract path' => $this->fieldPackContractPath,
            'Doctrine mapping contract path' => $this->doctrineMappingContractPath,
            'schema mirror contract path' => $this->schemaMirrorContractPath,
            'Exposing bridge contract path' => $this->exposingBridgeContractPath,
        ] as $label => $path) {
            $this->assertRelativePath($path, $label);
        }

        foreach ($this->requiredFieldPacks as $fieldPack) {
            if (!ObjectFieldPackName::isKnown($fieldPack)) {
                throw new \InvalidArgumentException(sprintf('Unknown Objecting required field pack "%s" in backend import contract.', $fieldPack));
            }
        }

        if (null !== $this->titleAliasProfile && !ObjectTitleAliasProfileName::isKnown($this->titleAliasProfile)) {
            throw new \InvalidArgumentException(sprintf('Unknown Objecting title-alias profile "%s" in backend import contract.', $this->titleAliasProfile));
        }

        foreach ([
            'required field packs' => $this->requiredFieldPacks,
            'import artifacts' => $this->importArtifacts,
            'quality gates' => $this->qualityGates,
            'required composer scripts' => $this->requiredComposerScripts,
        ] as $label => $values) {
            if ([] === $values) {
                throw new \InvalidArgumentException(sprintf('Objecting backend import contract %s cannot be empty.', $label));
            }
            if (array_values(array_unique($values)) !== $values) {
                throw new \InvalidArgumentException(sprintf('Objecting backend import contract has duplicate %s.', $label));
            }
            foreach ($values as $value) {
                if ('' === $value) {
                    throw new \InvalidArgumentException(sprintf('Objecting backend import contract %s cannot contain empty entries.', $label));
                }
            }
        }

        if (null !== $this->titleAliasProfile && !in_array(ObjectFieldPackName::TITLE, $this->requiredFieldPacks, true)) {
            throw new \InvalidArgumentException('Objecting backend import contract declares a title-alias profile without object_title in required field packs.');
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

    public function backendProjectRoot(): string
    {
        return $this->backendProjectRoot;
    }

    public function packageName(): string
    {
        return $this->packageName;
    }

    public function packageConstraint(): string
    {
        return $this->packageConstraint;
    }

    public function adoptionPacketPath(): string
    {
        return $this->adoptionPacketPath;
    }

    public function fieldPackContractPath(): string
    {
        return $this->fieldPackContractPath;
    }

    public function doctrineMappingContractPath(): string
    {
        return $this->doctrineMappingContractPath;
    }

    public function schemaMirrorContractPath(): string
    {
        return $this->schemaMirrorContractPath;
    }

    public function exposingBridgeContractPath(): string
    {
        return $this->exposingBridgeContractPath;
    }

    /** @return list<string> */
    public function requiredFieldPacks(): array
    {
        return $this->requiredFieldPacks;
    }

    /** @return list<string> */
    public function importArtifacts(): array
    {
        return $this->importArtifacts;
    }

    /** @return list<string> */
    public function qualityGates(): array
    {
        return $this->qualityGates;
    }

    /** @return list<string> */
    public function requiredComposerScripts(): array
    {
        return $this->requiredComposerScripts;
    }

    public function titleAliasProfile(): ?string
    {
        return $this->titleAliasProfile;
    }

    public function backendOwnsRuntime(): bool
    {
        return $this->backendOwnsRuntime;
    }

    public function objectingOwnsSystemFields(): bool
    {
        return $this->objectingOwnsSystemFields;
    }

    public function importInformational(): bool
    {
        return $this->importInformational;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'component' => $this->component,
            'business_stem' => $this->businessStem,
            'namespace' => $this->namespace,
            'entity_class' => $this->entityClass,
            'backend_project_root' => $this->backendProjectRoot,
            'package_name' => $this->packageName,
            'package_constraint' => $this->packageConstraint,
            'adoption_packet_path' => $this->adoptionPacketPath,
            'field_pack_contract_path' => $this->fieldPackContractPath,
            'doctrine_mapping_contract_path' => $this->doctrineMappingContractPath,
            'schema_mirror_contract_path' => $this->schemaMirrorContractPath,
            'exposing_bridge_contract_path' => $this->exposingBridgeContractPath,
            'required_field_packs' => $this->requiredFieldPacks,
            'import_artifacts' => $this->importArtifacts,
            'quality_gates' => $this->qualityGates,
            'required_composer_scripts' => $this->requiredComposerScripts,
            'title_alias_profile' => $this->titleAliasProfile,
            'backend_owns_runtime' => $this->backendOwnsRuntime,
            'objecting_owns_system_fields' => $this->objectingOwnsSystemFields,
            'import_informational' => $this->importInformational,
        ];
    }

    private function assertRelativePath(string $path, string $label): void
    {
        if ('' === $path || str_starts_with($path, '/') || str_starts_with($path, '\\') || str_contains($path, '..')) {
            throw new \InvalidArgumentException(sprintf('Objecting backend import contract %s must be a safe relative path.', $label));
        }
    }
}
