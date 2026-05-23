<?php

declare(strict_types=1);

namespace App\Objecting\ValueObject;

final readonly class ObjectBackendMigrationCommandPacket
{
    /**
     * @param list<string> $targetComponents
     * @param list<string> $pilotComponents
     * @param list<string> $objectingArtifacts
     * @param list<string> $backendArtifacts
     * @param list<string> $codexInstructions
     * @param list<string> $qualityGates
     * @param list<string> $forbiddenActions
     * @param list<string> $deferredTokens
     */
    public function __construct(
        private string $name,
        private string $packageName,
        private string $sourceAudit,
        private array $targetComponents,
        private array $pilotComponents,
        private array $objectingArtifacts,
        private array $backendArtifacts,
        private array $codexInstructions,
        private array $qualityGates,
        private array $forbiddenActions,
        private array $deferredTokens,
        private bool $touchedFilesOnly = true,
        private bool $cumulativeForBackupOnly = true,
        private bool $destructiveRepositoryCleanupForbidden = true,
        private bool $siblingComponentsCanBeModified = true,
        private bool $objectingCanBeModified = false,
        private bool $exposingCanBeModified = false,
    ) {
        foreach ([
            'name' => $this->name,
            'package name' => $this->packageName,
            'source audit' => $this->sourceAudit,
        ] as $label => $value) {
            if ('' === $value) {
                throw new \InvalidArgumentException(sprintf('Objecting backend migration command packet %s cannot be empty.', $label));
            }
        }

        foreach ([
            'target components' => $this->targetComponents,
            'pilot components' => $this->pilotComponents,
            'Objecting artifacts' => $this->objectingArtifacts,
            'backend artifacts' => $this->backendArtifacts,
            'Codex instructions' => $this->codexInstructions,
            'quality gates' => $this->qualityGates,
            'forbidden actions' => $this->forbiddenActions,
            'deferred tokens' => $this->deferredTokens,
        ] as $label => $values) {
            if ([] === $values) {
                throw new \InvalidArgumentException(sprintf('Objecting backend migration command packet %s cannot be empty.', $label));
            }
            if (array_values(array_unique($values)) !== $values) {
                throw new \InvalidArgumentException(sprintf('Objecting backend migration command packet has duplicate %s.', $label));
            }
            foreach ($values as $value) {
                if ('' === $value) {
                    throw new \InvalidArgumentException(sprintf('Objecting backend migration command packet %s cannot contain empty entries.', $label));
                }
            }
        }

        foreach ($this->targetComponents as $component) {
            $this->assertPascalCase($component, 'target component');
        }
        foreach ($this->pilotComponents as $component) {
            $this->assertPascalCase($component, 'pilot component');
            if (!in_array($component, $this->targetComponents, true)) {
                throw new \InvalidArgumentException(sprintf('Objecting backend migration command packet pilot component "%s" must also be listed as a target component.', $component));
            }
        }

        foreach (array_merge($this->objectingArtifacts, $this->backendArtifacts) as $artifact) {
            $this->assertRelativePath($artifact, 'artifact');
        }

        foreach ([ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT, ObjectFieldPackName::TITLE] as $baselinePack) {
            if (!in_array($baselinePack, $this->backendArtifacts, true) && !in_array($baselinePack, $this->codexInstructions, true)) {
                throw new \InvalidArgumentException(sprintf('Objecting backend migration command packet must mention baseline pack "%s".', $baselinePack));
            }
        }
    }

    public function name(): string
    {
        return $this->name;
    }

    public function packageName(): string
    {
        return $this->packageName;
    }

    public function sourceAudit(): string
    {
        return $this->sourceAudit;
    }

    /** @return list<string> */
    public function targetComponents(): array
    {
        return $this->targetComponents;
    }

    /** @return list<string> */
    public function pilotComponents(): array
    {
        return $this->pilotComponents;
    }

    /** @return list<string> */
    public function objectingArtifacts(): array
    {
        return $this->objectingArtifacts;
    }

    /** @return list<string> */
    public function backendArtifacts(): array
    {
        return $this->backendArtifacts;
    }

    /** @return list<string> */
    public function codexInstructions(): array
    {
        return $this->codexInstructions;
    }

    /** @return list<string> */
    public function qualityGates(): array
    {
        return $this->qualityGates;
    }

    /** @return list<string> */
    public function forbiddenActions(): array
    {
        return $this->forbiddenActions;
    }

    /** @return list<string> */
    public function deferredTokens(): array
    {
        return $this->deferredTokens;
    }

    public function touchedFilesOnly(): bool
    {
        return $this->touchedFilesOnly;
    }

    public function cumulativeForBackupOnly(): bool
    {
        return $this->cumulativeForBackupOnly;
    }

    public function destructiveRepositoryCleanupForbidden(): bool
    {
        return $this->destructiveRepositoryCleanupForbidden;
    }

    public function siblingComponentsCanBeModified(): bool
    {
        return $this->siblingComponentsCanBeModified;
    }

    public function objectingCanBeModified(): bool
    {
        return $this->objectingCanBeModified;
    }

    public function exposingCanBeModified(): bool
    {
        return $this->exposingCanBeModified;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'package_name' => $this->packageName,
            'source_audit' => $this->sourceAudit,
            'target_components' => $this->targetComponents,
            'pilot_components' => $this->pilotComponents,
            'objecting_artifacts' => $this->objectingArtifacts,
            'backend_artifacts' => $this->backendArtifacts,
            'codex_instructions' => $this->codexInstructions,
            'quality_gates' => $this->qualityGates,
            'forbidden_actions' => $this->forbiddenActions,
            'deferred_tokens' => $this->deferredTokens,
            'touched_files_only' => $this->touchedFilesOnly,
            'cumulative_for_backup_only' => $this->cumulativeForBackupOnly,
            'destructive_repository_cleanup_forbidden' => $this->destructiveRepositoryCleanupForbidden,
            'sibling_components_can_be_modified' => $this->siblingComponentsCanBeModified,
            'objecting_can_be_modified' => $this->objectingCanBeModified,
            'exposing_can_be_modified' => $this->exposingCanBeModified,
        ];
    }

    private function assertPascalCase(string $value, string $label): void
    {
        if (1 !== preg_match('/^[A-Z][A-Za-z0-9]*$/', $value)) {
            throw new \InvalidArgumentException(sprintf('Objecting backend migration command packet %s must be PascalCase.', $label));
        }
    }

    private function assertRelativePath(string $path, string $label): void
    {
        if ('' === $path || str_starts_with($path, '/') || str_starts_with($path, '\\') || str_contains($path, '..')) {
            throw new \InvalidArgumentException(sprintf('Objecting backend migration command packet %s must be a safe relative path.', $label));
        }
    }
}
