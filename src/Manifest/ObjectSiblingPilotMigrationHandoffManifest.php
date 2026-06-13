<?php

declare(strict_types=1);

namespace App\Objecting\Manifest;

final readonly class ObjectSiblingPilotMigrationHandoffManifest
{
    /**
     * @param list<string> $pilotComponents
     * @param list<string> $lockedObjectingArtifacts
     * @param list<string> $requiredBackendArtifacts
     * @param list<string> $targetFieldPacks
     * @param list<string> $titleAliasTokens
     * @param list<string> $deferredTokens
     * @param list<string> $qualityGates
     * @param list<string> $forbiddenActions
     */
    public function __construct(
        private string $nameEntity,
        private string $packageName,
        private string $objectingBaseline,
        private string $sourceAudit,
        private array $pilotComponents,
        private array $lockedObjectingArtifacts,
        private array $requiredBackendArtifacts,
        private array $targetFieldPacks,
        private array $titleAliasTokens,
        private array $deferredTokens,
        private array $qualityGates,
        private array $forbiddenActions,
        private bool $objectingLocked = true,
        private bool $exposingLocked = true,
        private bool $siblingComponentsCanBeModified = true,
        private bool $touchedFilesOnly = true,
        private bool $cumulativeForBackupOnly = true,
        private bool $destructiveRepositoryCleanupForbidden = true,
    ) {
        foreach ([
            'nameEntity' => $this->nameEntity,
            'package nameEntity' => $this->packageName,
            'Objecting baseline' => $this->objectingBaseline,
            'source audit' => $this->sourceAudit,
        ] as $label => $value) {
            if ('' === $value) {
                throw new \InvalidArgumentException(sprintf('Objecting sibling pilot migration handoff %s cannot be empty.', $label));
            }
        }

        foreach ([
            'pilot components' => $this->pilotComponents,
            'locked Objecting artifacts' => $this->lockedObjectingArtifacts,
            'required backend artifacts' => $this->requiredBackendArtifacts,
            'target field packs' => $this->targetFieldPacks,
            'title alias tokens' => $this->titleAliasTokens,
            'deferred tokens' => $this->deferredTokens,
            'quality gates' => $this->qualityGates,
            'forbidden actions' => $this->forbiddenActions,
        ] as $label => $values) {
            if ([] === $values) {
                throw new \InvalidArgumentException(sprintf('Objecting sibling pilot migration handoff %s cannot be empty.', $label));
            }
            if (array_values(array_unique($values)) !== $values) {
                throw new \InvalidArgumentException(sprintf('Objecting sibling pilot migration handoff has duplicate %s.', $label));
            }
            foreach ($values as $value) {
                if ('' === $value) {
                    throw new \InvalidArgumentException(sprintf('Objecting sibling pilot migration handoff %s cannot contain empty entries.', $label));
                }
            }
        }

        if (!str_ends_with($this->sourceAudit, '.md')) {
            throw new \InvalidArgumentException('Objecting sibling pilot migration handoff source audit must be a Markdown file.');
        }

        foreach ($this->pilotComponents as $component) {
            if (1 !== preg_match('/^[A-Z][A-Za-z0-9]*$/', $component)) {
                throw new \InvalidArgumentException(sprintf('Objecting sibling pilot migration handoff component "%s" must be PascalCase.', $component));
            }
        }

        foreach (array_merge($this->lockedObjectingArtifacts, $this->requiredBackendArtifacts) as $path) {
            if ('' === $path || str_starts_with($path, '/') || str_starts_with($path, '\\') || str_contains($path, '..')) {
                throw new \InvalidArgumentException(sprintf('Objecting sibling pilot migration handoff path "%s" must be a safe relative path.', $path));
            }
        }

        foreach ([ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT, ObjectFieldPackName::TITLE] as $baselinePack) {
            if (!in_array($baselinePack, $this->targetFieldPacks, true)) {
                throw new \InvalidArgumentException(sprintf('Objecting sibling pilot migration handoff target field packs must include baseline pack "%s".', $baselinePack));
            }
        }
    }

    public function nameEntity(): string
    {
        return $this->nameEntity;
    }

    public function packageName(): string
    {
        return $this->packageName;
    }

    public function objectingBaseline(): string
    {
        return $this->objectingBaseline;
    }

    public function sourceAudit(): string
    {
        return $this->sourceAudit;
    }

    /** @return list<string> */
    public function pilotComponents(): array
    {
        return $this->pilotComponents;
    }

    /** @return list<string> */
    public function lockedObjectingArtifacts(): array
    {
        return $this->lockedObjectingArtifacts;
    }

    /** @return list<string> */
    public function requiredBackendArtifacts(): array
    {
        return $this->requiredBackendArtifacts;
    }

    /** @return list<string> */
    public function targetFieldPacks(): array
    {
        return $this->targetFieldPacks;
    }

    /** @return list<string> */
    public function titleAliasTokens(): array
    {
        return $this->titleAliasTokens;
    }

    /** @return list<string> */
    public function deferredTokens(): array
    {
        return $this->deferredTokens;
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

    public function objectingLocked(): bool
    {
        return $this->objectingLocked;
    }

    public function exposingLocked(): bool
    {
        return $this->exposingLocked;
    }

    public function siblingComponentsCanBeModified(): bool
    {
        return $this->siblingComponentsCanBeModified;
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

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'nameEntity' => $this->nameEntity,
            'package_name' => $this->packageName,
            'objecting_baseline' => $this->objectingBaseline,
            'source_audit' => $this->sourceAudit,
            'pilot_components' => $this->pilotComponents,
            'locked_objecting_artifacts' => $this->lockedObjectingArtifacts,
            'required_backend_artifacts' => $this->requiredBackendArtifacts,
            'target_field_packs' => $this->targetFieldPacks,
            'title_alias_tokens' => $this->titleAliasTokens,
            'deferred_tokens' => $this->deferredTokens,
            'quality_gates' => $this->qualityGates,
            'forbidden_actions' => $this->forbiddenActions,
            'objecting_locked' => $this->objectingLocked,
            'exposing_locked' => $this->exposingLocked,
            'sibling_components_can_be_modified' => $this->siblingComponentsCanBeModified,
            'touched_files_only' => $this->touchedFilesOnly,
            'cumulative_for_backup_only' => $this->cumulativeForBackupOnly,
            'destructive_repository_cleanup_forbidden' => $this->destructiveRepositoryCleanupForbidden,
        ];
    }
}
