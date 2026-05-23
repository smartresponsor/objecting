<?php

declare(strict_types=1);

namespace App\Objecting\ValueObject;

final readonly class ObjectReleaseClosureManifest
{
    /**
     * @param list<string> $qualityGates
     * @param list<string> $releaseArtifacts
     * @param list<string> $consumerContracts
     * @param list<string> $nextTracks
     */
    public function __construct(
        private string $closureCandidate,
        private string $packageName,
        private string $namespacePrefix,
        private string $bundleClass,
        private string $cumulativeArchive,
        private string $touchedArchive,
        private array $qualityGates,
        private array $releaseArtifacts,
        private array $consumerContracts,
        private array $nextTracks,
        private bool $fieldPackFoundationOnly = true,
        private bool $objectTitleCanonical = true,
        private bool $legacyFree = true,
        private bool $backendRuntimeOwner = true,
        private bool $exposingSeparated = true,
    ) {
        foreach ([
            'closure candidate' => $this->closureCandidate,
            'package name' => $this->packageName,
            'namespace prefix' => $this->namespacePrefix,
            'bundle class' => $this->bundleClass,
            'cumulative archive' => $this->cumulativeArchive,
            'touched archive' => $this->touchedArchive,
        ] as $label => $value) {
            if ('' === $value) {
                throw new \InvalidArgumentException(sprintf('Objecting release closure manifest %s cannot be empty.', $label));
            }
        }

        if (1 !== preg_match('/^objecting_wave[0-9]+_[a-z0-9_]+$/', $this->closureCandidate)) {
            throw new \InvalidArgumentException('Objecting release closure candidate must use objecting_waveN_snake_case naming.');
        }

        if (!str_ends_with($this->cumulativeArchive, '_cumulative.zip')) {
            throw new \InvalidArgumentException('Objecting release closure cumulative archive must end with _cumulative.zip.');
        }

        if (!str_ends_with($this->touchedArchive, '_touched.zip')) {
            throw new \InvalidArgumentException('Objecting release closure touched archive must end with _touched.zip.');
        }

        foreach ([
            'quality gates' => $this->qualityGates,
            'release artifacts' => $this->releaseArtifacts,
            'consumer contracts' => $this->consumerContracts,
            'next tracks' => $this->nextTracks,
        ] as $label => $values) {
            if ([] === $values) {
                throw new \InvalidArgumentException(sprintf('Objecting release closure manifest %s cannot be empty.', $label));
            }
            if (array_values(array_unique($values)) !== $values) {
                throw new \InvalidArgumentException(sprintf('Objecting release closure manifest has duplicate %s.', $label));
            }
            foreach ($values as $value) {
                if ('' === $value) {
                    throw new \InvalidArgumentException(sprintf('Objecting release closure manifest %s cannot contain empty entries.', $label));
                }
            }
        }
    }

    public function closureCandidate(): string
    {
        return $this->closureCandidate;
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

    /** @return list<string> */
    public function qualityGates(): array
    {
        return $this->qualityGates;
    }

    /** @return list<string> */
    public function releaseArtifacts(): array
    {
        return $this->releaseArtifacts;
    }

    /** @return list<string> */
    public function consumerContracts(): array
    {
        return $this->consumerContracts;
    }

    /** @return list<string> */
    public function nextTracks(): array
    {
        return $this->nextTracks;
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

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'closure_candidate' => $this->closureCandidate,
            'package_name' => $this->packageName,
            'namespace_prefix' => $this->namespacePrefix,
            'bundle_class' => $this->bundleClass,
            'cumulative_archive' => $this->cumulativeArchive,
            'touched_archive' => $this->touchedArchive,
            'quality_gates' => $this->qualityGates,
            'release_artifacts' => $this->releaseArtifacts,
            'consumer_contracts' => $this->consumerContracts,
            'next_tracks' => $this->nextTracks,
            'field_pack_foundation_only' => $this->fieldPackFoundationOnly,
            'object_title_canonical' => $this->objectTitleCanonical,
            'legacy_free' => $this->legacyFree,
            'backend_runtime_owner' => $this->backendRuntimeOwner,
            'exposing_separated' => $this->exposingSeparated,
        ];
    }
}
