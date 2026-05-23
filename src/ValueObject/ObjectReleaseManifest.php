<?php

declare(strict_types=1);

namespace App\Objecting\ValueObject;

final readonly class ObjectReleaseManifest
{
    /**
     * @param list<string> $qualityGates
     * @param list<string> $requiredComposerScripts
     * @param list<string> $consumerContracts
     */
    public function __construct(
        private string $releaseCandidate,
        private string $packageName,
        private string $namespacePrefix,
        private string $bundleClass,
        private string $cumulativeArchive,
        private string $touchedArchive,
        private array $qualityGates,
        private array $requiredComposerScripts,
        private array $consumerContracts,
        private bool $fieldPackFoundationOnly = true,
        private bool $legacyFree = true,
    ) {
        foreach ([
            'release candidate' => $this->releaseCandidate,
            'package name' => $this->packageName,
            'namespace prefix' => $this->namespacePrefix,
            'bundle class' => $this->bundleClass,
            'cumulative archive' => $this->cumulativeArchive,
            'touched archive' => $this->touchedArchive,
        ] as $label => $value) {
            if ('' === $value) {
                throw new \InvalidArgumentException(sprintf('Objecting release manifest %s cannot be empty.', $label));
            }
        }

        if (1 !== preg_match('/^objecting_wave[0-9]+_[a-z0-9_]+$/', $this->releaseCandidate)) {
            throw new \InvalidArgumentException('Objecting release candidate must use objecting_waveN_snake_case naming.');
        }

        if (!str_ends_with($this->cumulativeArchive, '_cumulative.zip')) {
            throw new \InvalidArgumentException('Objecting release manifest cumulative archive must end with _cumulative.zip.');
        }

        if (!str_ends_with($this->touchedArchive, '_touched.zip')) {
            throw new \InvalidArgumentException('Objecting release manifest touched archive must end with _touched.zip.');
        }

        foreach ([
            'quality gates' => $this->qualityGates,
            'required composer scripts' => $this->requiredComposerScripts,
            'consumer contracts' => $this->consumerContracts,
        ] as $label => $values) {
            if ([] === $values) {
                throw new \InvalidArgumentException(sprintf('Objecting release manifest %s cannot be empty.', $label));
            }
            if (array_values(array_unique($values)) !== $values) {
                throw new \InvalidArgumentException(sprintf('Objecting release manifest has duplicate %s.', $label));
            }
            foreach ($values as $value) {
                if ('' === $value) {
                    throw new \InvalidArgumentException(sprintf('Objecting release manifest %s cannot contain empty entries.', $label));
                }
            }
        }
    }

    public function releaseCandidate(): string
    {
        return $this->releaseCandidate;
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
    public function requiredComposerScripts(): array
    {
        return $this->requiredComposerScripts;
    }

    /** @return list<string> */
    public function consumerContracts(): array
    {
        return $this->consumerContracts;
    }

    public function fieldPackFoundationOnly(): bool
    {
        return $this->fieldPackFoundationOnly;
    }

    public function legacyFree(): bool
    {
        return $this->legacyFree;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'release_candidate' => $this->releaseCandidate,
            'package_name' => $this->packageName,
            'namespace_prefix' => $this->namespacePrefix,
            'bundle_class' => $this->bundleClass,
            'cumulative_archive' => $this->cumulativeArchive,
            'touched_archive' => $this->touchedArchive,
            'quality_gates' => $this->qualityGates,
            'required_composer_scripts' => $this->requiredComposerScripts,
            'consumer_contracts' => $this->consumerContracts,
            'field_pack_foundation_only' => $this->fieldPackFoundationOnly,
            'legacy_free' => $this->legacyFree,
        ];
    }
}
