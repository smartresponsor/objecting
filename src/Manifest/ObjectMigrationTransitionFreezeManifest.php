<?php

declare(strict_types=1);

namespace App\Objecting\Manifest;

final readonly class ObjectMigrationTransitionFreezeManifest
{
    /**
     * @param list<string> $pilotComponents
     * @param list<string> $lockedObjectingArtifacts
     * @param list<string> $handoffCommands
     * @param list<string> $forbiddenActions
     */
    public function __construct(
        private string $name,
        private string $packageName,
        private string $objectingBaseline,
        private string $closureCandidate,
        private string $cumulativeArchive,
        private string $touchedArchive,
        private string $nextTrack,
        private array $pilotComponents,
        private array $lockedObjectingArtifacts,
        private array $handoffCommands,
        private array $forbiddenActions,
        private bool $objectingFrozen = true,
        private bool $exposingFrozen = true,
        private bool $backendMigrationOpen = true,
        private bool $touchedFilesOnly = true,
        private bool $cumulativeForBackupOnly = true,
        private bool $destructiveRepositoryCleanupForbidden = true,
    ) {
        foreach ([
            'name' => $this->name,
            'package name' => $this->packageName,
            'Objecting baseline' => $this->objectingBaseline,
            'closure candidate' => $this->closureCandidate,
            'cumulative archive' => $this->cumulativeArchive,
            'touched archive' => $this->touchedArchive,
            'next track' => $this->nextTrack,
        ] as $label => $value) {
            if ('' === $value) {
                throw new \InvalidArgumentException(sprintf('Objecting migration transition freeze %s cannot be empty.', $label));
            }
        }

        if (!str_ends_with($this->cumulativeArchive, '_cumulative.zip')) {
            throw new \InvalidArgumentException('Objecting migration transition freeze cumulative archive must end with _cumulative.zip.');
        }

        if (!str_ends_with($this->touchedArchive, '_touched.zip')) {
            throw new \InvalidArgumentException('Objecting migration transition freeze touched archive must end with _touched.zip.');
        }

        foreach ([
            'pilot components' => $this->pilotComponents,
            'locked Objecting artifacts' => $this->lockedObjectingArtifacts,
            'handoff commands' => $this->handoffCommands,
            'forbidden actions' => $this->forbiddenActions,
        ] as $label => $values) {
            if ([] === $values) {
                throw new \InvalidArgumentException(sprintf('Objecting migration transition freeze %s cannot be empty.', $label));
            }
            if (array_values(array_unique($values)) !== $values) {
                throw new \InvalidArgumentException(sprintf('Objecting migration transition freeze has duplicate %s.', $label));
            }
            foreach ($values as $value) {
                if ('' === $value) {
                    throw new \InvalidArgumentException(sprintf('Objecting migration transition freeze %s cannot contain empty entries.', $label));
                }
            }
        }

        foreach ($this->pilotComponents as $component) {
            if (1 !== preg_match('/^[A-Z][A-Za-z0-9]*$/', $component)) {
                throw new \InvalidArgumentException(sprintf('Objecting migration transition freeze component "%s" must be PascalCase.', $component));
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

    public function objectingBaseline(): string
    {
        return $this->objectingBaseline;
    }

    public function closureCandidate(): string
    {
        return $this->closureCandidate;
    }

    public function cumulativeArchive(): string
    {
        return $this->cumulativeArchive;
    }

    public function touchedArchive(): string
    {
        return $this->touchedArchive;
    }

    public function nextTrack(): string
    {
        return $this->nextTrack;
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
    public function handoffCommands(): array
    {
        return $this->handoffCommands;
    }

    /** @return list<string> */
    public function forbiddenActions(): array
    {
        return $this->forbiddenActions;
    }

    public function objectingFrozen(): bool
    {
        return $this->objectingFrozen;
    }

    public function exposingFrozen(): bool
    {
        return $this->exposingFrozen;
    }

    public function backendMigrationOpen(): bool
    {
        return $this->backendMigrationOpen;
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
            'name' => $this->name,
            'package_name' => $this->packageName,
            'objecting_baseline' => $this->objectingBaseline,
            'closure_candidate' => $this->closureCandidate,
            'cumulative_archive' => $this->cumulativeArchive,
            'touched_archive' => $this->touchedArchive,
            'next_track' => $this->nextTrack,
            'pilot_components' => $this->pilotComponents,
            'locked_objecting_artifacts' => $this->lockedObjectingArtifacts,
            'handoff_commands' => $this->handoffCommands,
            'forbidden_actions' => $this->forbiddenActions,
            'objecting_frozen' => $this->objectingFrozen,
            'exposing_frozen' => $this->exposingFrozen,
            'backend_migration_open' => $this->backendMigrationOpen,
            'touched_files_only' => $this->touchedFilesOnly,
            'cumulative_for_backup_only' => $this->cumulativeForBackupOnly,
            'destructive_repository_cleanup_forbidden' => $this->destructiveRepositoryCleanupForbidden,
        ];
    }
}
