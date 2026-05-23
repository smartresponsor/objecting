<?php

declare(strict_types=1);

namespace App\Objecting\ValueObject;

final readonly class ObjectBackendCloneCleanupContract
{
    /**
     * @param list<string> $cloneFiles
     * @param list<string> $replacementFieldPacks
     * @param list<string> $cleanupArtifacts
     * @param list<string> $qualityGates
     * @param list<string> $requiredComposerScripts
     */
    public function __construct(
        private string $component,
        private string $businessStem,
        private string $namespace,
        private string $packageName,
        private array $cloneFiles,
        private array $replacementFieldPacks,
        private array $cleanupArtifacts,
        private array $qualityGates,
        private array $requiredComposerScripts,
        private bool $touchedFilesOnly = true,
        private bool $cumulativeForBackupOnly = true,
        private bool $backendOwnsRuntime = true,
        private bool $objectingOwnsSystemFields = true,
        private bool $destructiveRepositoryCleanupForbidden = true,
    ) {
        foreach ([
            'component' => $this->component,
            'business stem' => $this->businessStem,
            'namespace' => $this->namespace,
            'package name' => $this->packageName,
        ] as $label => $value) {
            if ('' === $value) {
                throw new \InvalidArgumentException(sprintf('Objecting backend clone-cleanup contract %s cannot be empty.', $label));
            }
        }

        foreach (['component' => $this->component, 'business stem' => $this->businessStem] as $label => $value) {
            if (1 !== preg_match('/^[A-Z][A-Za-z0-9]*$/', $value)) {
                throw new \InvalidArgumentException(sprintf('Objecting backend clone-cleanup contract %s must be PascalCase.', $label));
            }
        }

        if ($this->namespace !== 'App\\'.$this->component) {
            throw new \InvalidArgumentException('Objecting backend clone-cleanup contract namespace must match App\\<Component>.');
        }

        foreach ($this->replacementFieldPacks as $fieldPack) {
            if (!ObjectFieldPackName::isKnown($fieldPack)) {
                throw new \InvalidArgumentException(sprintf('Unknown Objecting replacement field pack "%s" in backend clone-cleanup contract.', $fieldPack));
            }
        }

        foreach ([
            'clone files' => $this->cloneFiles,
            'replacement field packs' => $this->replacementFieldPacks,
            'cleanup artifacts' => $this->cleanupArtifacts,
            'quality gates' => $this->qualityGates,
            'required composer scripts' => $this->requiredComposerScripts,
        ] as $label => $values) {
            if ([] === $values) {
                throw new \InvalidArgumentException(sprintf('Objecting backend clone-cleanup contract %s cannot be empty.', $label));
            }
            if (array_values(array_unique($values)) !== $values) {
                throw new \InvalidArgumentException(sprintf('Objecting backend clone-cleanup contract has duplicate %s.', $label));
            }
            foreach ($values as $value) {
                if ('' === $value) {
                    throw new \InvalidArgumentException(sprintf('Objecting backend clone-cleanup contract %s cannot contain empty entries.', $label));
                }
            }
        }

        foreach ($this->cloneFiles as $cloneFile) {
            $this->assertRelativePath($cloneFile, 'clone file');
            if (!str_starts_with($cloneFile, 'src/EntityTrait/') && !str_starts_with($cloneFile, 'src/EntityInterface/')) {
                throw new \InvalidArgumentException(sprintf('Objecting backend clone-cleanup clone file "%s" must live under src/EntityTrait or src/EntityInterface.', $cloneFile));
            }
            if (!str_contains(basename($cloneFile), 'Object')) {
                throw new \InvalidArgumentException(sprintf('Objecting backend clone-cleanup clone file "%s" must be an Object* clone surface.', $cloneFile));
            }
        }

        foreach ($this->cleanupArtifacts as $cleanupArtifact) {
            $this->assertRelativePath($cleanupArtifact, 'cleanup artifact');
        }

        foreach ([ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT, ObjectFieldPackName::TITLE] as $baselinePack) {
            if (!in_array($baselinePack, $this->replacementFieldPacks, true)) {
                throw new \InvalidArgumentException(sprintf('Objecting backend clone-cleanup replacement packs must include baseline pack "%s".', $baselinePack));
            }
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

    /** @return list<string> */
    public function cloneFiles(): array
    {
        return $this->cloneFiles;
    }

    /** @return list<string> */
    public function replacementFieldPacks(): array
    {
        return $this->replacementFieldPacks;
    }

    /** @return list<string> */
    public function cleanupArtifacts(): array
    {
        return $this->cleanupArtifacts;
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

    public function touchedFilesOnly(): bool
    {
        return $this->touchedFilesOnly;
    }

    public function cumulativeForBackupOnly(): bool
    {
        return $this->cumulativeForBackupOnly;
    }

    public function backendOwnsRuntime(): bool
    {
        return $this->backendOwnsRuntime;
    }

    public function objectingOwnsSystemFields(): bool
    {
        return $this->objectingOwnsSystemFields;
    }

    public function destructiveRepositoryCleanupForbidden(): bool
    {
        return $this->destructiveRepositoryCleanupForbidden;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'component' => $this->component,
            'business_stem' => $this->businessStem,
            'namespace' => $this->namespace,
            'package_name' => $this->packageName,
            'clone_files' => $this->cloneFiles,
            'replacement_field_packs' => $this->replacementFieldPacks,
            'cleanup_artifacts' => $this->cleanupArtifacts,
            'quality_gates' => $this->qualityGates,
            'required_composer_scripts' => $this->requiredComposerScripts,
            'touched_files_only' => $this->touchedFilesOnly,
            'cumulative_for_backup_only' => $this->cumulativeForBackupOnly,
            'backend_owns_runtime' => $this->backendOwnsRuntime,
            'objecting_owns_system_fields' => $this->objectingOwnsSystemFields,
            'destructive_repository_cleanup_forbidden' => $this->destructiveRepositoryCleanupForbidden,
        ];
    }

    private function assertRelativePath(string $path, string $label): void
    {
        if ('' === $path || str_starts_with($path, '/') || str_starts_with($path, '\\') || str_contains($path, '..')) {
            throw new \InvalidArgumentException(sprintf('Objecting backend clone-cleanup contract %s must be a safe relative path.', $label));
        }
    }
}
