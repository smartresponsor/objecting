<?php

declare(strict_types=1);

namespace App\Objecting\Manifest;

final readonly class ObjectBackendAdoptionManifest
{
    /**
     * @param list<string> $fieldPacks
     * @param list<string> $effectiveFieldPacks
     */
    public function __construct(
        private string $component,
        private string $businessStem,
        private string $namespace,
        private string $entityClass,
        private string $tableName,
        private array $fieldPacks,
        private array $effectiveFieldPacks,
        private ?string $fieldPackProfile = null,
        private ?string $titleAliasProfile = null,
        private ?string $exposingContractPath = null,
        private bool $standaloneReady = true,
    ) {
        foreach ([
            'component' => $this->component,
            'business stem' => $this->businessStem,
            'namespace' => $this->namespace,
            'entity class' => $this->entityClass,
            'table name' => $this->tableName,
        ] as $label => $value) {
            if ('' === $value) {
                throw new \InvalidArgumentException(sprintf('Objecting backend adoption manifest %s cannot be empty.', $label));
            }
        }

        foreach (['component' => $this->component, 'business stem' => $this->businessStem] as $label => $value) {
            if (1 !== preg_match('/^[A-Z][A-Za-z0-9]*$/', $value)) {
                throw new \InvalidArgumentException(sprintf('Objecting backend adoption manifest %s must be PascalCase.', $label));
            }
        }

        if (!str_starts_with($this->namespace, 'App\\')) {
            throw new \InvalidArgumentException('Objecting backend adoption manifest namespace must start with App\\.');
        }

        if (!str_starts_with($this->entityClass, $this->namespace.'\\Entity\\')) {
            throw new \InvalidArgumentException(sprintf('Entity class "%s" must be inside "%s\\Entity".', $this->entityClass, $this->namespace));
        }

        if (!str_ends_with($this->entityClass, '\\'.$this->businessStem)) {
            throw new \InvalidArgumentException(sprintf('Entity class "%s" must end with business stem "%s".', $this->entityClass, $this->businessStem));
        }

        if (1 !== preg_match('/^[a-z][a-z0-9_]*$/', $this->tableName)) {
            throw new \InvalidArgumentException(sprintf('Objecting backend adoption table name "%s" must be lowercase snake_case.', $this->tableName));
        }

        foreach (['explicit' => $this->fieldPacks, 'effective' => $this->effectiveFieldPacks] as $label => $fieldPacks) {
            if (array_values(array_unique($fieldPacks)) !== $fieldPacks) {
                throw new \InvalidArgumentException(sprintf('Objecting backend adoption manifest has duplicate %s field packs.', $label));
            }

            foreach ($fieldPacks as $fieldPack) {
                if (!ObjectFieldPackName::isKnown($fieldPack)) {
                    throw new \InvalidArgumentException(sprintf('Unknown Objecting %s field pack "%s" in backend adoption manifest.', $label, $fieldPack));
                }
            }
        }

        if (null !== $this->fieldPackProfile && !ObjectFieldPackProfileName::isKnown($this->fieldPackProfile)) {
            throw new \InvalidArgumentException(sprintf('Unknown Objecting field-pack profile "%s" in backend adoption manifest.', $this->fieldPackProfile));
        }

        if (null !== $this->titleAliasProfile && !ObjectTitleAliasProfileName::isKnown($this->titleAliasProfile)) {
            throw new \InvalidArgumentException(sprintf('Unknown Objecting title-alias profile "%s" in backend adoption manifest.', $this->titleAliasProfile));
        }

        if (null !== $this->titleAliasProfile && !in_array(ObjectFieldPackName::TITLE, $this->effectiveFieldPacks, true)) {
            throw new \InvalidArgumentException('Objecting backend adoption manifest declares a title-alias profile without object_title in effective field packs.');
        }

        if (null !== $this->exposingContractPath && '' === $this->exposingContractPath) {
            throw new \InvalidArgumentException('Objecting backend adoption exposing contract path cannot be empty when declared.');
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

    /** @return list<string> */
    public function fieldPacks(): array
    {
        return $this->fieldPacks;
    }

    /** @return list<string> */
    public function effectiveFieldPacks(): array
    {
        return $this->effectiveFieldPacks;
    }

    public function fieldPackProfile(): ?string
    {
        return $this->fieldPackProfile;
    }

    public function titleAliasProfile(): ?string
    {
        return $this->titleAliasProfile;
    }

    public function exposingContractPath(): ?string
    {
        return $this->exposingContractPath;
    }

    public function standaloneReady(): bool
    {
        return $this->standaloneReady;
    }

    public function toConsumerContract(): ObjectFieldPackConsumerContract
    {
        return new ObjectFieldPackConsumerContract(
            component: $this->component,
            businessStem: $this->businessStem,
            entityClass: $this->entityClass,
            fieldPacks: $this->fieldPacks,
            fieldPackProfile: $this->fieldPackProfile,
            titleAliasProfile: $this->titleAliasProfile,
        );
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
            'field_pack_profile' => $this->fieldPackProfile,
            'explicit_field_packs' => $this->fieldPacks,
            'effective_field_packs' => $this->effectiveFieldPacks,
            'title_alias_profile' => $this->titleAliasProfile,
            'exposing_contract_path' => $this->exposingContractPath,
            'standalone_ready' => $this->standaloneReady,
        ];
    }
}
