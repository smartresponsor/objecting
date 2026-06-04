<?php

declare(strict_types=1);

namespace App\Objecting\Manifest;

final readonly class ObjectBackendHandoffManifest
{
    /**
     * @param list<string> $qualityGates
     * @param list<string> $requiredComposerScripts
     */
    public function __construct(
        private string $component,
        private string $businessStem,
        private string $namespace,
        private string $packageName,
        private string $packageConstraint,
        private string $backendProjectRoot,
        private string $adoptionManifestPath,
        private string $readinessManifestPath,
        private array $qualityGates,
        private array $requiredComposerScripts,
        private ?string $exposingContractPath = null,
        private bool $standaloneReady = true,
    ) {
        foreach ([
            'component' => $this->component,
            'business stem' => $this->businessStem,
            'namespace' => $this->namespace,
            'package name' => $this->packageName,
            'package constraint' => $this->packageConstraint,
            'backend project root' => $this->backendProjectRoot,
            'adoption manifest path' => $this->adoptionManifestPath,
            'readiness manifest path' => $this->readinessManifestPath,
        ] as $label => $value) {
            if ('' === $value) {
                throw new \InvalidArgumentException(sprintf('Objecting backend handoff manifest %s cannot be empty.', $label));
            }
        }

        foreach (['component' => $this->component, 'business stem' => $this->businessStem] as $label => $value) {
            if (1 !== preg_match('/^[A-Z][A-Za-z0-9]*$/', $value)) {
                throw new \InvalidArgumentException(sprintf('Objecting backend handoff manifest %s must be PascalCase.', $label));
            }
        }

        if (!str_starts_with($this->namespace, 'App\\')) {
            throw new \InvalidArgumentException('Objecting backend handoff manifest namespace must start with App\\.');
        }

        foreach ([
            'adoption manifest path' => $this->adoptionManifestPath,
            'readiness manifest path' => $this->readinessManifestPath,
        ] as $label => $path) {
            $this->assertRelativePath($path, $label);
        }

        foreach (['quality gates' => $this->qualityGates, 'required composer scripts' => $this->requiredComposerScripts] as $label => $values) {
            if ([] === $values) {
                throw new \InvalidArgumentException(sprintf('Objecting backend handoff manifest %s cannot be empty.', $label));
            }
            if (array_values(array_unique($values)) !== $values) {
                throw new \InvalidArgumentException(sprintf('Objecting backend handoff manifest has duplicate %s.', $label));
            }
            foreach ($values as $value) {
                if ('' === $value) {
                    throw new \InvalidArgumentException(sprintf('Objecting backend handoff manifest %s cannot contain empty entries.', $label));
                }
            }
        }

        if (null !== $this->exposingContractPath) {
            $this->assertRelativePath($this->exposingContractPath, 'exposing contract path');
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

    public function packageName(): string
    {
        return $this->packageName;
    }

    public function packageConstraint(): string
    {
        return $this->packageConstraint;
    }

    public function backendProjectRoot(): string
    {
        return $this->backendProjectRoot;
    }

    public function adoptionManifestPath(): string
    {
        return $this->adoptionManifestPath;
    }

    public function readinessManifestPath(): string
    {
        return $this->readinessManifestPath;
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
            'package_name' => $this->packageName,
            'package_constraint' => $this->packageConstraint,
            'backend_project_root' => $this->backendProjectRoot,
            'adoption_manifest_path' => $this->adoptionManifestPath,
            'readiness_manifest_path' => $this->readinessManifestPath,
            'quality_gates' => $this->qualityGates,
            'required_composer_scripts' => $this->requiredComposerScripts,
            'exposing_contract_path' => $this->exposingContractPath,
            'standalone_ready' => $this->standaloneReady,
        ];
    }

    private function assertRelativePath(string $path, string $label): void
    {
        if ('' === $path || str_starts_with($path, '/') || str_starts_with($path, '\\') || str_contains($path, '..')) {
            throw new \InvalidArgumentException(sprintf('Objecting backend handoff manifest %s must be a safe relative path.', $label));
        }
    }
}
