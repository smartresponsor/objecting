<?php

declare(strict_types=1);

namespace App\Objecting\Contract;

use App\Objecting\ValueObject\ObjectFieldPackName;

final readonly class ObjectExposingBridgeContract
{
    /**
     * @param list<string> $requiredFieldPacks
     * @param list<string> $exportArtifacts
     */
    public function __construct(
        private string $component,
        private string $businessStem,
        private string $namespace,
        private string $entityClass,
        private string $fieldPackContractPath,
        private string $doctrineMappingContractPath,
        private string $schemaMirrorContractPath,
        private string $backendAdoptionPacketPath,
        private string $exposingOpenApiPath,
        private string $exposingSchemaMirrorPath,
        private string $openApiSchemaName,
        private string $titleAliasProfile,
        private array $requiredFieldPacks,
        private array $exportArtifacts,
        private bool $backendOwnsRuntime = true,
        private bool $objectingOwnsFieldPacks = true,
        private bool $exposingOwnsApiContract = true,
        private bool $bridgeInformational = true,
    ) {
        foreach ([
            'component' => $this->component,
            'business stem' => $this->businessStem,
            'namespace' => $this->namespace,
            'entity class' => $this->entityClass,
            'field-pack contract path' => $this->fieldPackContractPath,
            'Doctrine mapping contract path' => $this->doctrineMappingContractPath,
            'schema mirror contract path' => $this->schemaMirrorContractPath,
            'backend adoption packet path' => $this->backendAdoptionPacketPath,
            'Exposing OpenAPI path' => $this->exposingOpenApiPath,
            'Exposing schema mirror path' => $this->exposingSchemaMirrorPath,
            'OpenAPI schema name' => $this->openApiSchemaName,
            'title alias profile' => $this->titleAliasProfile,
        ] as $label => $value) {
            if ('' === $value) {
                throw new \InvalidArgumentException(sprintf('Objecting Exposing bridge contract %s cannot be empty.', $label));
            }
        }

        if (1 !== preg_match('/^[A-Z][A-Za-z0-9]*$/', $this->component)) {
            throw new \InvalidArgumentException('Objecting Exposing bridge contract component must be PascalCase.');
        }

        if (1 !== preg_match('/^[A-Z][A-Za-z0-9]*$/', $this->businessStem)) {
            throw new \InvalidArgumentException('Objecting Exposing bridge contract business stem must be PascalCase.');
        }

        if (!str_starts_with($this->namespace, 'App\\')) {
            throw new \InvalidArgumentException('Objecting Exposing bridge contract namespace must start with App\\.');
        }

        if (!str_starts_with($this->entityClass, $this->namespace.'\\Entity\\')) {
            throw new \InvalidArgumentException('Objecting Exposing bridge contract entity class must live under the backend Entity namespace.');
        }

        foreach ([
            'field-pack contract path' => $this->fieldPackContractPath,
            'Doctrine mapping contract path' => $this->doctrineMappingContractPath,
            'schema mirror contract path' => $this->schemaMirrorContractPath,
            'backend adoption packet path' => $this->backendAdoptionPacketPath,
            'Exposing OpenAPI path' => $this->exposingOpenApiPath,
            'Exposing schema mirror path' => $this->exposingSchemaMirrorPath,
        ] as $label => $path) {
            $this->assertRelativePath($path, $label);
        }

        foreach ([
            'required field packs' => $this->requiredFieldPacks,
            'export artifacts' => $this->exportArtifacts,
        ] as $label => $values) {
            if ([] === $values) {
                throw new \InvalidArgumentException(sprintf('Objecting Exposing bridge contract %s cannot be empty.', $label));
            }
            if (array_values(array_unique($values)) !== $values) {
                throw new \InvalidArgumentException(sprintf('Objecting Exposing bridge contract has duplicate %s.', $label));
            }
            foreach ($values as $value) {
                if ('' === $value) {
                    throw new \InvalidArgumentException(sprintf('Objecting Exposing bridge contract %s cannot contain empty entries.', $label));
                }
            }
        }

        foreach ($this->requiredFieldPacks as $fieldPack) {
            if (!ObjectFieldPackName::isKnown($fieldPack)) {
                throw new \InvalidArgumentException(sprintf('Unknown Objecting field pack "%s" in Exposing bridge contract.', $fieldPack));
            }
        }

        foreach ($this->exportArtifacts as $artifact) {
            $this->assertRelativePath($artifact, 'export artifact');
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

    public function backendAdoptionPacketPath(): string
    {
        return $this->backendAdoptionPacketPath;
    }

    public function exposingOpenApiPath(): string
    {
        return $this->exposingOpenApiPath;
    }

    public function exposingSchemaMirrorPath(): string
    {
        return $this->exposingSchemaMirrorPath;
    }

    public function openApiSchemaName(): string
    {
        return $this->openApiSchemaName;
    }

    public function titleAliasProfile(): string
    {
        return $this->titleAliasProfile;
    }

    /** @return list<string> */
    public function requiredFieldPacks(): array
    {
        return $this->requiredFieldPacks;
    }

    /** @return list<string> */
    public function exportArtifacts(): array
    {
        return $this->exportArtifacts;
    }

    public function backendOwnsRuntime(): bool
    {
        return $this->backendOwnsRuntime;
    }

    public function objectingOwnsFieldPacks(): bool
    {
        return $this->objectingOwnsFieldPacks;
    }

    public function exposingOwnsApiContract(): bool
    {
        return $this->exposingOwnsApiContract;
    }

    public function bridgeInformational(): bool
    {
        return $this->bridgeInformational;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'component' => $this->component,
            'business_stem' => $this->businessStem,
            'namespace' => $this->namespace,
            'entity_class' => $this->entityClass,
            'field_pack_contract_path' => $this->fieldPackContractPath,
            'doctrine_mapping_contract_path' => $this->doctrineMappingContractPath,
            'schema_mirror_contract_path' => $this->schemaMirrorContractPath,
            'backend_adoption_packet_path' => $this->backendAdoptionPacketPath,
            'exposing_openapi_path' => $this->exposingOpenApiPath,
            'exposing_schema_mirror_path' => $this->exposingSchemaMirrorPath,
            'openapi_schema_name' => $this->openApiSchemaName,
            'title_alias_profile' => $this->titleAliasProfile,
            'required_field_packs' => $this->requiredFieldPacks,
            'export_artifacts' => $this->exportArtifacts,
            'backend_owns_runtime' => $this->backendOwnsRuntime,
            'objecting_owns_field_packs' => $this->objectingOwnsFieldPacks,
            'exposing_owns_api_contract' => $this->exposingOwnsApiContract,
            'bridge_informational' => $this->bridgeInformational,
        ];
    }

    private function assertRelativePath(string $path, string $label): void
    {
        if ('' === $path || str_starts_with($path, '/') || str_starts_with($path, '\\') || str_contains($path, '..')) {
            throw new \InvalidArgumentException(sprintf('Objecting Exposing bridge contract %s must be a safe relative path.', $label));
        }
    }
}
