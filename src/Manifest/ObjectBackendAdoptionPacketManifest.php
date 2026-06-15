<?php

declare(strict_types=1);

namespace App\Objecting\Manifest;

use App\Objecting\ValueObject\ObjectFieldPackName;
use App\Objecting\ValueObject\ObjectTitleAliasProfileName;

final readonly class ObjectBackendAdoptionPacketManifest
{
    /**
     * @param list<string> $requiredFieldPacks
     * @param list<string> $qualityGates
     * @param list<string> $requiredComposerScripts
     * @param list<string> $packetArtifacts
     */
    public function __construct(
        private string $component,
        private string $businessStem,
        private string $namespace,
        private string $backendProjectRoot,
        private string $packageName,
        private string $packageConstraint,
        private string $fieldPackContractPath,
        private string $readinessManifestPath,
        private string $adoptionManifestPath,
        private string $handoffManifestPath,
        private string $releaseClosureManifestPath,
        private array $requiredFieldPacks,
        private array $qualityGates,
        private array $requiredComposerScripts,
        private array $packetArtifacts,
        private ?string $titleAliasProfile = null,
        private ?string $exposingContractPath = null,
        private bool $standaloneReady = true,
    ) {
        foreach ([
            'component' => $this->component,
            'business stem' => $this->businessStem,
            'namespace' => $this->namespace,
            'backend project root' => $this->backendProjectRoot,
            'package name' => $this->packageName,
            'package constraint' => $this->packageConstraint,
            'field-pack contract path' => $this->fieldPackContractPath,
            'readiness manifest path' => $this->readinessManifestPath,
            'adoption manifest path' => $this->adoptionManifestPath,
            'handoff manifest path' => $this->handoffManifestPath,
            'release closure manifest path' => $this->releaseClosureManifestPath,
        ] as $label => $value) {
            if ('' === $value) {
                throw new \InvalidArgumentException(sprintf('Objecting backend adoption packet %s cannot be empty.', $label));
            }
        }

        foreach (['component' => $this->component, 'business stem' => $this->businessStem] as $label => $value) {
            if (1 !== preg_match('/^[A-Z][A-Za-z0-9]*$/', $value)) {
                throw new \InvalidArgumentException(sprintf('Objecting backend adoption packet %s must be PascalCase.', $label));
            }
        }

        if (!str_starts_with($this->namespace, 'App\\')) {
            throw new \InvalidArgumentException('Objecting backend adoption packet namespace must start with App\\.');
        }

        foreach ([
            'field-pack contract path' => $this->fieldPackContractPath,
            'readiness manifest path' => $this->readinessManifestPath,
            'adoption manifest path' => $this->adoptionManifestPath,
            'handoff manifest path' => $this->handoffManifestPath,
            'release closure manifest path' => $this->releaseClosureManifestPath,
        ] as $label => $path) {
            $this->assertRelativePath($path, $label);
        }

        if (null !== $this->exposingContractPath) {
            $this->assertRelativePath($this->exposingContractPath, 'exposing contract path');
        }

        foreach ($this->requiredFieldPacks as $fieldPack) {
            if (!ObjectFieldPackName::isKnown($fieldPack)) {
                throw new \InvalidArgumentException(sprintf('Unknown Objecting required field pack "%s" in backend adoption packet.', $fieldPack));
            }
        }

        if (null !== $this->titleAliasProfile && !ObjectTitleAliasProfileName::isKnown($this->titleAliasProfile)) {
            throw new \InvalidArgumentException(sprintf('Unknown Objecting title-alias profile "%s" in backend adoption packet.', $this->titleAliasProfile));
        }

        foreach ([
            'required field packs' => $this->requiredFieldPacks,
            'quality gates' => $this->qualityGates,
            'required composer scripts' => $this->requiredComposerScripts,
            'packet artifacts' => $this->packetArtifacts,
        ] as $label => $values) {
            if ([] === $values) {
                throw new \InvalidArgumentException(sprintf('Objecting backend adoption packet %s cannot be empty.', $label));
            }
            if (array_values(array_unique($values)) !== $values) {
                throw new \InvalidArgumentException(sprintf('Objecting backend adoption packet has duplicate %s.', $label));
            }
            foreach ($values as $value) {
                if ('' === $value) {
                    throw new \InvalidArgumentException(sprintf('Objecting backend adoption packet %s cannot contain empty entries.', $label));
                }
            }
        }

        if (null !== $this->titleAliasProfile && !in_array(ObjectFieldPackName::TITLE, $this->requiredFieldPacks, true)) {
            throw new \InvalidArgumentException('Objecting backend adoption packet declares a title-alias profile without object_title in required field packs.');
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

    public function fieldPackContractPath(): string
    {
        return $this->fieldPackContractPath;
    }

    public function readinessManifestPath(): string
    {
        return $this->readinessManifestPath;
    }

    public function adoptionManifestPath(): string
    {
        return $this->adoptionManifestPath;
    }

    public function handoffManifestPath(): string
    {
        return $this->handoffManifestPath;
    }

    public function releaseClosureManifestPath(): string
    {
        return $this->releaseClosureManifestPath;
    }

    /** @return list<string> */
    public function requiredFieldPacks(): array
    {
        return $this->requiredFieldPacks;
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

    /** @return list<string> */
    public function packetArtifacts(): array
    {
        return $this->packetArtifacts;
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

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'component' => $this->component,
            'business_stem' => $this->businessStem,
            'namespace' => $this->namespace,
            'backend_project_root' => $this->backendProjectRoot,
            'package_name' => $this->packageName,
            'package_constraint' => $this->packageConstraint,
            'field_pack_contract_path' => $this->fieldPackContractPath,
            'readiness_manifest_path' => $this->readinessManifestPath,
            'adoption_manifest_path' => $this->adoptionManifestPath,
            'handoff_manifest_path' => $this->handoffManifestPath,
            'release_closure_manifest_path' => $this->releaseClosureManifestPath,
            'required_field_packs' => $this->requiredFieldPacks,
            'quality_gates' => $this->qualityGates,
            'required_composer_scripts' => $this->requiredComposerScripts,
            'packet_artifacts' => $this->packetArtifacts,
            'title_alias_profile' => $this->titleAliasProfile,
            'exposing_contract_path' => $this->exposingContractPath,
            'standalone_ready' => $this->standaloneReady,
        ];
    }

    private function assertRelativePath(string $path, string $label): void
    {
        if ('' === $path || str_starts_with($path, '/') || str_starts_with($path, '\\') || str_contains($path, '..')) {
            throw new \InvalidArgumentException(sprintf('Objecting backend adoption packet %s must be a safe relative path.', $label));
        }
    }
}
