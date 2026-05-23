<?php

declare(strict_types=1);

namespace App\Objecting\ValueObject;

final readonly class ObjectRcStabilizationManifest
{
    /**
     * @param list<string> $qualityGates
     * @param list<string> $requiredComposerScripts
     * @param list<string> $finalEntrypoints
     */
    public function __construct(
        private string $stabilizationCandidate,
        private string $packageName,
        private string $namespacePrefix,
        private string $bundleClass,
        private string $releaseClosurePath,
        private string $releaseReadinessPath,
        private string $backendImportPath,
        private string $adoptionPacketPath,
        private string $exposingBridgePath,
        private string $schemaMirrorPath,
        private string $doctrineMappingPath,
        private array $qualityGates,
        private array $requiredComposerScripts,
        private array $finalEntrypoints,
        private bool $fieldPackFoundationOnly = true,
        private bool $objectTitleCanonical = true,
        private bool $legacyFree = true,
        private bool $backendRuntimeOwner = true,
        private bool $exposingSeparated = true,
        private bool $rcMarkerPending = true,
    ) {
        foreach ([
            'stabilization candidate' => $this->stabilizationCandidate,
            'package name' => $this->packageName,
            'namespace prefix' => $this->namespacePrefix,
            'bundle class' => $this->bundleClass,
            'release closure path' => $this->releaseClosurePath,
            'release readiness path' => $this->releaseReadinessPath,
            'backend import path' => $this->backendImportPath,
            'adoption packet path' => $this->adoptionPacketPath,
            'Exposing bridge path' => $this->exposingBridgePath,
            'schema mirror path' => $this->schemaMirrorPath,
            'Doctrine mapping path' => $this->doctrineMappingPath,
        ] as $label => $value) {
            if ('' === $value) {
                throw new \InvalidArgumentException(sprintf('Objecting RC stabilization manifest %s cannot be empty.', $label));
            }
        }

        if (1 !== preg_match('/^objecting_wave[0-9]+_[a-z0-9_]+$/', $this->stabilizationCandidate)) {
            throw new \InvalidArgumentException('Objecting RC stabilization candidate must use objecting_waveN_snake_case naming.');
        }

        foreach ([
            'release closure path' => $this->releaseClosurePath,
            'release readiness path' => $this->releaseReadinessPath,
            'backend import path' => $this->backendImportPath,
            'adoption packet path' => $this->adoptionPacketPath,
            'Exposing bridge path' => $this->exposingBridgePath,
            'schema mirror path' => $this->schemaMirrorPath,
            'Doctrine mapping path' => $this->doctrineMappingPath,
        ] as $label => $path) {
            $this->assertRelativePath($path, $label);
        }

        foreach ([
            'quality gates' => $this->qualityGates,
            'required composer scripts' => $this->requiredComposerScripts,
            'final entrypoints' => $this->finalEntrypoints,
        ] as $label => $values) {
            if ([] === $values) {
                throw new \InvalidArgumentException(sprintf('Objecting RC stabilization manifest %s cannot be empty.', $label));
            }
            if (array_values(array_unique($values)) !== $values) {
                throw new \InvalidArgumentException(sprintf('Objecting RC stabilization manifest has duplicate %s.', $label));
            }
            foreach ($values as $value) {
                if ('' === $value) {
                    throw new \InvalidArgumentException(sprintf('Objecting RC stabilization manifest %s cannot contain empty entries.', $label));
                }
            }
        }
    }

    public function stabilizationCandidate(): string
    {
        return $this->stabilizationCandidate;
    }

    public function packageName(): string
    {
        return $this->packageName;
    }

    public function namespacePrefix(): string
    {
        return $this->namespacePrefix;
    }

    public function bundleClass(): string
    {
        return $this->bundleClass;
    }

    public function releaseClosurePath(): string
    {
        return $this->releaseClosurePath;
    }

    public function releaseReadinessPath(): string
    {
        return $this->releaseReadinessPath;
    }

    public function backendImportPath(): string
    {
        return $this->backendImportPath;
    }

    public function adoptionPacketPath(): string
    {
        return $this->adoptionPacketPath;
    }

    public function exposingBridgePath(): string
    {
        return $this->exposingBridgePath;
    }

    public function schemaMirrorPath(): string
    {
        return $this->schemaMirrorPath;
    }

    public function doctrineMappingPath(): string
    {
        return $this->doctrineMappingPath;
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
    public function finalEntrypoints(): array
    {
        return $this->finalEntrypoints;
    }

    public function fieldPackFoundationOnly(): bool
    {
        return $this->fieldPackFoundationOnly;
    }

    public function objectTitleCanonical(): bool
    {
        return $this->objectTitleCanonical;
    }

    public function legacyFree(): bool
    {
        return $this->legacyFree;
    }

    public function backendRuntimeOwner(): bool
    {
        return $this->backendRuntimeOwner;
    }

    public function exposingSeparated(): bool
    {
        return $this->exposingSeparated;
    }

    public function rcMarkerPending(): bool
    {
        return $this->rcMarkerPending;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'stabilization_candidate' => $this->stabilizationCandidate,
            'package_name' => $this->packageName,
            'namespace_prefix' => $this->namespacePrefix,
            'bundle_class' => $this->bundleClass,
            'release_closure_path' => $this->releaseClosurePath,
            'release_readiness_path' => $this->releaseReadinessPath,
            'backend_import_path' => $this->backendImportPath,
            'adoption_packet_path' => $this->adoptionPacketPath,
            'exposing_bridge_path' => $this->exposingBridgePath,
            'schema_mirror_path' => $this->schemaMirrorPath,
            'doctrine_mapping_path' => $this->doctrineMappingPath,
            'quality_gates' => $this->qualityGates,
            'required_composer_scripts' => $this->requiredComposerScripts,
            'final_entrypoints' => $this->finalEntrypoints,
            'field_pack_foundation_only' => $this->fieldPackFoundationOnly,
            'object_title_canonical' => $this->objectTitleCanonical,
            'legacy_free' => $this->legacyFree,
            'backend_runtime_owner' => $this->backendRuntimeOwner,
            'exposing_separated' => $this->exposingSeparated,
            'rc_marker_pending' => $this->rcMarkerPending,
        ];
    }

    private function assertRelativePath(string $path, string $label): void
    {
        if ('' === $path || str_starts_with($path, '/') || str_starts_with($path, '\\') || str_contains($path, '..')) {
            throw new \InvalidArgumentException(sprintf('Objecting RC stabilization manifest %s must be a safe relative path.', $label));
        }
    }
}
