<?php

declare(strict_types=1);

namespace App\Objecting\Manifest;

final readonly class ObjectRcMarkerManifest
{
    /**
     * @param list<string> $qualityGates
     * @param list<string> $requiredComposerScripts
     * @param list<string> $finalEntrypoints
     */
    public function __construct(
        private string $rcName,
        private string $rcCandidate,
        private string $packageName,
        private string $namespacePrefix,
        private string $bundleClass,
        private string $cumulativeArchive,
        private string $touchedArchive,
        private string $applyScript,
        private string $rcStabilizationPath,
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
        private bool $rcAccepted = true,
    ) {
        foreach ([
            'RC nameEntity' => $this->rcName,
            'RC candidate' => $this->rcCandidate,
            'package nameEntity' => $this->packageName,
            'namespace prefix' => $this->namespacePrefix,
            'bundle class' => $this->bundleClass,
            'cumulative archive' => $this->cumulativeArchive,
            'touched archive' => $this->touchedArchive,
            'apply script' => $this->applyScript,
            'RC stabilization path' => $this->rcStabilizationPath,
            'release closure path' => $this->releaseClosurePath,
            'release readiness path' => $this->releaseReadinessPath,
            'backend import path' => $this->backendImportPath,
            'adoption packet path' => $this->adoptionPacketPath,
            'Exposing bridge path' => $this->exposingBridgePath,
            'schema mirror path' => $this->schemaMirrorPath,
            'Doctrine mapping path' => $this->doctrineMappingPath,
        ] as $label => $value) {
            if ('' === $value) {
                throw new \InvalidArgumentException(sprintf('Objecting RC marker manifest %s cannot be empty.', $label));
            }
        }

        if (1 !== preg_match('/^objecting_rc[0-9]+$/', $this->rcName)) {
            throw new \InvalidArgumentException('Objecting RC marker nameEntity must use objecting_rcN naming.');
        }

        if (1 !== preg_match('/^objecting_wave[0-9]+_[a-z0-9_]+$/', $this->rcCandidate)) {
            throw new \InvalidArgumentException('Objecting RC marker candidate must use objecting_waveN_snake_case naming.');
        }

        foreach ([
            'cumulative archive' => $this->cumulativeArchive,
            'touched archive' => $this->touchedArchive,
            'apply script' => $this->applyScript,
            'RC stabilization path' => $this->rcStabilizationPath,
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

        if (!str_ends_with($this->cumulativeArchive, '_cumulative.zip')) {
            throw new \InvalidArgumentException('Objecting RC marker cumulative archive must end with _cumulative.zip.');
        }

        if (!str_ends_with($this->touchedArchive, '_touched.zip')) {
            throw new \InvalidArgumentException('Objecting RC marker touched archive must end with _touched.zip.');
        }

        if (!str_ends_with($this->applyScript, '.ps1')) {
            throw new \InvalidArgumentException('Objecting RC marker apply script must be a PowerShell script.');
        }

        foreach ([
            'quality gates' => $this->qualityGates,
            'required composer scripts' => $this->requiredComposerScripts,
            'final entrypoints' => $this->finalEntrypoints,
        ] as $label => $values) {
            if ([] === $values) {
                throw new \InvalidArgumentException(sprintf('Objecting RC marker manifest %s cannot be empty.', $label));
            }
            if (array_values(array_unique($values)) !== $values) {
                throw new \InvalidArgumentException(sprintf('Objecting RC marker manifest has duplicate %s.', $label));
            }
            foreach ($values as $value) {
                if ('' === $value) {
                    throw new \InvalidArgumentException(sprintf('Objecting RC marker manifest %s cannot contain empty entries.', $label));
                }
            }
        }
    }

    public function rcName(): string
    {
        return $this->rcName;
    }

    public function rcCandidate(): string
    {
        return $this->rcCandidate;
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

    public function cumulativeArchive(): string
    {
        return $this->cumulativeArchive;
    }

    public function touchedArchive(): string
    {
        return $this->touchedArchive;
    }

    public function applyScript(): string
    {
        return $this->applyScript;
    }

    public function rcStabilizationPath(): string
    {
        return $this->rcStabilizationPath;
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

    public function rcAccepted(): bool
    {
        return $this->rcAccepted;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'rc_name' => $this->rcName,
            'rc_candidate' => $this->rcCandidate,
            'package_name' => $this->packageName,
            'namespace_prefix' => $this->namespacePrefix,
            'bundle_class' => $this->bundleClass,
            'cumulative_archive' => $this->cumulativeArchive,
            'touched_archive' => $this->touchedArchive,
            'apply_script' => $this->applyScript,
            'rc_stabilization_path' => $this->rcStabilizationPath,
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
            'rc_accepted' => $this->rcAccepted,
        ];
    }

    private function assertRelativePath(string $path, string $label): void
    {
        if ('' === $path || str_starts_with($path, '/') || str_starts_with($path, '\\') || str_contains($path, '..')) {
            throw new \InvalidArgumentException(sprintf('Objecting RC marker manifest %s must be a safe relative path.', $label));
        }
    }
}
