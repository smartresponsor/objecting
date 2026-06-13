<?php

declare(strict_types=1);

namespace App\Objecting\Manifest;

final readonly class ObjectPlatformConstraintManifest
{
    /**
     * @param array<string, string> $requiredConstraints
     * @param array<string, string> $extraSymfony
     * @param list<string>          $forbiddenConstraints
     * @param list<string>          $qualityGates
     */
    public function __construct(
        private string $constraintCandidate,
        private string $packageName,
        private string $phpConstraint,
        private string $symfonyConstraint,
        private string $namespacePrefix,
        private string $bundleClass,
        private array $requiredConstraints,
        private array $extraSymfony,
        private array $forbiddenConstraints,
        private array $qualityGates,
        private bool $php84Only = true,
        private bool $symfony8Only = true,
        private bool $symfony7Forbidden = true,
        private bool $mixedSymfony78Forbidden = true,
        private bool $legacyFree = true,
    ) {
        foreach ([
            'constraint candidate' => $this->constraintCandidate,
            'package nameEntity' => $this->packageName,
            'PHP constraint' => $this->phpConstraint,
            'Symfony constraint' => $this->symfonyConstraint,
            'namespace prefix' => $this->namespacePrefix,
            'bundle class' => $this->bundleClass,
        ] as $label => $value) {
            if ('' === $value) {
                throw new \InvalidArgumentException(sprintf('Objecting platform constraint manifest %s cannot be empty.', $label));
            }
        }

        if (1 !== preg_match('/^objecting_wave[0-9]+_[a-z0-9_]+$/', $this->constraintCandidate)) {
            throw new \InvalidArgumentException('Objecting platform constraint candidate must use objecting_waveN_snake_case naming.');
        }

        if ('^8.4' !== $this->phpConstraint) {
            throw new \InvalidArgumentException('Objecting platform PHP constraint must be ^8.4.');
        }

        if ('^8.0' !== $this->symfonyConstraint) {
            throw new \InvalidArgumentException('Objecting platform Symfony constraint must be ^8.0.');
        }

        foreach ([
            'required constraints' => $this->requiredConstraints,
            'extra Symfony constraints' => $this->extraSymfony,
            'forbidden constraints' => $this->forbiddenConstraints,
            'quality gates' => $this->qualityGates,
        ] as $label => $values) {
            if ([] === $values) {
                throw new \InvalidArgumentException(sprintf('Objecting platform constraint manifest %s cannot be empty.', $label));
            }
        }
    }

    public function constraintCandidate(): string
    {
        return $this->constraintCandidate;
    }

    public function packageName(): string
    {
        return $this->packageName;
    }

    public function phpConstraint(): string
    {
        return $this->phpConstraint;
    }

    public function symfonyConstraint(): string
    {
        return $this->symfonyConstraint;
    }

    public function namespacePrefix(): string
    {
        return $this->namespacePrefix;
    }

    public function bundleClass(): string
    {
        return $this->bundleClass;
    }

    /** @return array<string, string> */
    public function requiredConstraints(): array
    {
        return $this->requiredConstraints;
    }

    /** @return array<string, string> */
    public function extraSymfony(): array
    {
        return $this->extraSymfony;
    }

    /** @return list<string> */
    public function forbiddenConstraints(): array
    {
        return $this->forbiddenConstraints;
    }

    /** @return list<string> */
    public function qualityGates(): array
    {
        return $this->qualityGates;
    }

    public function php84Only(): bool
    {
        return $this->php84Only;
    }

    public function symfony8Only(): bool
    {
        return $this->symfony8Only;
    }

    public function symfony7Forbidden(): bool
    {
        return $this->symfony7Forbidden;
    }

    public function mixedSymfony78Forbidden(): bool
    {
        return $this->mixedSymfony78Forbidden;
    }

    public function legacyFree(): bool
    {
        return $this->legacyFree;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'constraint_candidate' => $this->constraintCandidate,
            'package_name' => $this->packageName,
            'php_constraint' => $this->phpConstraint,
            'symfony_constraint' => $this->symfonyConstraint,
            'namespace_prefix' => $this->namespacePrefix,
            'bundle_class' => $this->bundleClass,
            'required_constraints' => $this->requiredConstraints,
            'extra_symfony' => $this->extraSymfony,
            'forbidden_constraints' => $this->forbiddenConstraints,
            'quality_gates' => $this->qualityGates,
            'php_84_only' => $this->php84Only,
            'symfony_8_only' => $this->symfony8Only,
            'symfony_7_forbidden' => $this->symfony7Forbidden,
            'mixed_symfony_7_8_forbidden' => $this->mixedSymfony78Forbidden,
            'legacy_free' => $this->legacyFree,
        ];
    }
}
