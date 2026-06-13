<?php

declare(strict_types=1);

namespace App\Objecting\Manifest;

final readonly class ObjectRc2MarkerManifest
{
    /**
     * @param list<string> $qualityGates
     * @param list<string> $requiredComposerScripts
     * @param list<string> $finalEntrypoints
     * @param list<string> $includedFieldPacks
     * @param list<string> $forbiddenFieldPacks
     * @param list<string> $deferredTokens
     */
    public function __construct(
        private string $rcName,
        private string $rcCandidate,
        private string $previousRcName,
        private string $packageName,
        private string $namespacePrefix,
        private string $bundleClass,
        private string $cumulativeArchive,
        private string $touchedArchive,
        private string $applyScript,
        private string $releaseClosurePath,
        private string $fieldPackManifestPath,
        private string $titleAliasManifestPath,
        private string $backendMigrationCommandPath,
        private string $backendCloneCleanupPath,
        private string $platformConstraintsPath,
        private array $qualityGates,
        private array $requiredComposerScripts,
        private array $finalEntrypoints,
        private array $includedFieldPacks,
        private array $forbiddenFieldPacks,
        private array $deferredTokens,
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
            'previous RC nameEntity' => $this->previousRcName,
            'package nameEntity' => $this->packageName,
            'namespace prefix' => $this->namespacePrefix,
            'bundle class' => $this->bundleClass,
            'cumulative archive' => $this->cumulativeArchive,
            'touched archive' => $this->touchedArchive,
            'apply script' => $this->applyScript,
            'release closure path' => $this->releaseClosurePath,
            'field-pack manifest path' => $this->fieldPackManifestPath,
            'title-alias manifest path' => $this->titleAliasManifestPath,
            'backend migration command path' => $this->backendMigrationCommandPath,
            'backend clone-cleanup path' => $this->backendCloneCleanupPath,
            'platform constraints path' => $this->platformConstraintsPath,
        ] as $label => $value) {
            if ('' === $value) {
                throw new \InvalidArgumentException(sprintf('Objecting RC2 marker manifest %s cannot be empty.', $label));
            }
        }

        if (1 !== preg_match('/^objecting_rc[0-9]+$/', $this->rcName)) {
            throw new \InvalidArgumentException('Objecting RC2 marker nameEntity must use objecting_rcN naming.');
        }

        if (1 !== preg_match('/^objecting_rc[0-9]+$/', $this->previousRcName)) {
            throw new \InvalidArgumentException('Objecting RC2 previous marker must use objecting_rcN naming.');
        }

        if (1 !== preg_match('/^objecting_wave[0-9]+_[a-z0-9_]+$/', $this->rcCandidate)) {
            throw new \InvalidArgumentException('Objecting RC2 marker candidate must use objecting_waveN_snake_case naming.');
        }

        if (!str_starts_with($this->namespacePrefix, 'App\\Objecting')) {
            throw new \InvalidArgumentException('Objecting RC2 marker namespace prefix must start with App\\Objecting.');
        }

        foreach ([
            'cumulative archive' => $this->cumulativeArchive,
            'touched archive' => $this->touchedArchive,
            'apply script' => $this->applyScript,
            'release closure path' => $this->releaseClosurePath,
            'field-pack manifest path' => $this->fieldPackManifestPath,
            'title-alias manifest path' => $this->titleAliasManifestPath,
            'backend migration command path' => $this->backendMigrationCommandPath,
            'backend clone-cleanup path' => $this->backendCloneCleanupPath,
            'platform constraints path' => $this->platformConstraintsPath,
        ] as $label => $path) {
            $this->assertRelativePath($path, $label);
        }

        if (!str_ends_with($this->cumulativeArchive, '_cumulative.zip')) {
            throw new \InvalidArgumentException('Objecting RC2 marker cumulative archive must end with _cumulative.zip.');
        }

        if (!str_ends_with($this->touchedArchive, '_touched.zip')) {
            throw new \InvalidArgumentException('Objecting RC2 marker touched archive must end with _touched.zip.');
        }

        if (!str_ends_with($this->applyScript, '.ps1')) {
            throw new \InvalidArgumentException('Objecting RC2 marker apply script must be a PowerShell script.');
        }

        foreach ([
            'quality gates' => $this->qualityGates,
            'required composer scripts' => $this->requiredComposerScripts,
            'final entrypoints' => $this->finalEntrypoints,
            'included field packs' => $this->includedFieldPacks,
            'forbidden field packs' => $this->forbiddenFieldPacks,
            'deferred tokens' => $this->deferredTokens,
        ] as $label => $values) {
            if ([] === $values) {
                throw new \InvalidArgumentException(sprintf('Objecting RC2 marker manifest %s cannot be empty.', $label));
            }
            if (array_values(array_unique($values)) !== $values) {
                throw new \InvalidArgumentException(sprintf('Objecting RC2 marker manifest has duplicate %s.', $label));
            }
            foreach ($values as $value) {
                if ('' === $value) {
                    throw new \InvalidArgumentException(sprintf('Objecting RC2 marker manifest %s cannot contain empty entries.', $label));
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

    public function previousRcName(): string
    {
        return $this->previousRcName;
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

    public function releaseClosurePath(): string
    {
        return $this->releaseClosurePath;
    }

    public function fieldPackManifestPath(): string
    {
        return $this->fieldPackManifestPath;
    }

    public function titleAliasManifestPath(): string
    {
        return $this->titleAliasManifestPath;
    }

    public function backendMigrationCommandPath(): string
    {
        return $this->backendMigrationCommandPath;
    }

    public function backendCloneCleanupPath(): string
    {
        return $this->backendCloneCleanupPath;
    }

    public function platformConstraintsPath(): string
    {
        return $this->platformConstraintsPath;
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

    /** @return list<string> */
    public function includedFieldPacks(): array
    {
        return $this->includedFieldPacks;
    }

    /** @return list<string> */
    public function forbiddenFieldPacks(): array
    {
        return $this->forbiddenFieldPacks;
    }

    /** @return list<string> */
    public function deferredTokens(): array
    {
        return $this->deferredTokens;
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
            'previous_rc_name' => $this->previousRcName,
            'package_name' => $this->packageName,
            'namespace_prefix' => $this->namespacePrefix,
            'bundle_class' => $this->bundleClass,
            'cumulative_archive' => $this->cumulativeArchive,
            'touched_archive' => $this->touchedArchive,
            'apply_script' => $this->applyScript,
            'release_closure_path' => $this->releaseClosurePath,
            'field_pack_manifest_path' => $this->fieldPackManifestPath,
            'title_alias_manifest_path' => $this->titleAliasManifestPath,
            'backend_migration_command_path' => $this->backendMigrationCommandPath,
            'backend_clone_cleanup_path' => $this->backendCloneCleanupPath,
            'platform_constraints_path' => $this->platformConstraintsPath,
            'quality_gates' => $this->qualityGates,
            'required_composer_scripts' => $this->requiredComposerScripts,
            'final_entrypoints' => $this->finalEntrypoints,
            'included_field_packs' => $this->includedFieldPacks,
            'forbidden_field_packs' => $this->forbiddenFieldPacks,
            'deferred_tokens' => $this->deferredTokens,
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
            throw new \InvalidArgumentException(sprintf('Objecting RC2 marker manifest %s must be a safe relative path.', $label));
        }
    }
}
