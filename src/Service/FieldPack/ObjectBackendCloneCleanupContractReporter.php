<?php

declare(strict_types=1);

namespace App\Objecting\Service\FieldPack;

use App\Objecting\ServiceInterface\FieldPack\ObjectBackendCloneCleanupContractReporterInterface;
use App\Objecting\ValueObject\ObjectBackendCloneCleanupContract;
use App\Objecting\ValueObject\ObjectBackendCloneCleanupReport;
use App\Objecting\ValueObject\ObjectFieldPackName;
use App\Objecting\ValueObject\ObjectPackageSurface;

final readonly class ObjectBackendCloneCleanupContractReporter implements ObjectBackendCloneCleanupContractReporterInterface
{
    public function report(ObjectBackendCloneCleanupContract $contract): ObjectBackendCloneCleanupReport
    {
        $checks = [];
        $blockingReasons = [];

        if (ObjectPackageSurface::COMPOSER_PACKAGE !== $contract->packageName()) {
            $blockingReasons[] = sprintf('Backend clone-cleanup package "%s" must be "%s".', $contract->packageName(), ObjectPackageSurface::COMPOSER_PACKAGE);
        }
        $checks[] = 'objecting_package_name';

        $expectedNamespace = 'App\\'.$contract->component();
        if ($contract->namespace() !== $expectedNamespace) {
            $blockingReasons[] = sprintf('Backend clone-cleanup namespace "%s" must equal "%s".', $contract->namespace(), $expectedNamespace);
        }
        $checks[] = 'backend_component_namespace';

        foreach ($contract->cloneFiles() as $cloneFile) {
            if (!str_starts_with($cloneFile, 'src/EntityTrait/') && !str_starts_with($cloneFile, 'src/EntityInterface/')) {
                $blockingReasons[] = sprintf('Clone file "%s" must live under src/EntityTrait or src/EntityInterface.', $cloneFile);
            }
            if (!str_contains(basename($cloneFile), 'Object')) {
                $blockingReasons[] = sprintf('Clone file "%s" must be an Object* clone surface.', $cloneFile);
            }
        }
        $checks[] = 'object_clone_surfaces';

        foreach ([ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT, ObjectFieldPackName::TITLE] as $baselinePack) {
            if (!in_array($baselinePack, $contract->replacementFieldPacks(), true)) {
                $blockingReasons[] = sprintf('Clone cleanup replacement packs must include "%s".', $baselinePack);
            }
        }
        $checks[] = 'baseline_replacement_field_packs';

        foreach (['composer dump-autoload', 'composer test:quality'] as $requiredGate) {
            if (!in_array($requiredGate, $contract->qualityGates(), true)) {
                $blockingReasons[] = sprintf('Backend clone-cleanup quality gates must include "%s".', $requiredGate);
            }
        }
        $checks[] = 'backend_quality_gates';

        foreach (['test:canon', 'test:quality'] as $requiredScript) {
            if (!in_array($requiredScript, $contract->requiredComposerScripts(), true)) {
                $blockingReasons[] = sprintf('Backend clone-cleanup required composer scripts must include "%s".', $requiredScript);
            }
        }
        $checks[] = 'backend_composer_scripts';

        foreach ($contract->cleanupArtifacts() as $artifact) {
            if (!str_starts_with($artifact, 'resources/objecting/') && !str_starts_with($artifact, 'src/')) {
                $blockingReasons[] = sprintf('Cleanup artifact "%s" must be a source or Objecting resource path.', $artifact);
            }
        }
        $checks[] = 'cleanup_artifact_paths';

        if (!$contract->touchedFilesOnly()) {
            $blockingReasons[] = 'Backend clone cleanup must be touched-files only.';
        }
        $checks[] = 'touched_files_only';

        if (!$contract->cumulativeForBackupOnly()) {
            $blockingReasons[] = 'Backend clone cleanup cumulative archive must be backup/reference only.';
        }
        $checks[] = 'cumulative_backup_only';

        if (!$contract->backendOwnsRuntime()) {
            $blockingReasons[] = 'Backend clone cleanup must keep backend runtime ownership.';
        }
        $checks[] = 'backend_runtime_owner';

        if (!$contract->objectingOwnsSystemFields()) {
            $blockingReasons[] = 'Backend clone cleanup must recognize Objecting as system-field owner.';
        }
        $checks[] = 'objecting_system_field_owner';

        if (!$contract->destructiveRepositoryCleanupForbidden()) {
            $blockingReasons[] = 'Destructive repository cleanup must remain forbidden.';
        }
        $checks[] = 'destructive_repository_cleanup_forbidden';

        return new ObjectBackendCloneCleanupReport(
            contract: $contract,
            checks: $checks,
            blockingReasons: array_values(array_unique($blockingReasons)),
        );
    }
}
