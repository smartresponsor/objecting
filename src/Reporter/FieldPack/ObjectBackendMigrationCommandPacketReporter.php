<?php

declare(strict_types=1);

namespace App\Objecting\Reporter\FieldPack;

use App\Objecting\Packet\ObjectBackendMigrationCommandPacket;
use App\Objecting\Report\ObjectBackendMigrationCommandReport;
use App\Objecting\ReporterInterface\FieldPack\ObjectBackendMigrationCommandPacketReporterInterface;
use App\Objecting\Surface\ObjectPackageSurface;
use App\Objecting\ValueObject\ObjectFieldPackName;

final readonly class ObjectBackendMigrationCommandPacketReporter implements ObjectBackendMigrationCommandPacketReporterInterface
{
    public function report(ObjectBackendMigrationCommandPacket $packet): ObjectBackendMigrationCommandReport
    {
        $checks = [];
        $blockingReasons = [];

        if (ObjectPackageSurface::COMPOSER_PACKAGE !== $packet->packageName()) {
            $blockingReasons[] = sprintf('Backend migration command package "%s" must be "%s".', $packet->packageName(), ObjectPackageSurface::COMPOSER_PACKAGE);
        }
        $checks[] = 'objecting_package_name';

        if (!str_ends_with($packet->sourceAudit(), '.md')) {
            $blockingReasons[] = 'Backend migration command source audit must be a Markdown report.';
        }
        $checks[] = 'source_audit_markdown';

        foreach (['Addressing', 'Taxating'] as $pilotComponent) {
            if (!in_array($pilotComponent, $packet->pilotComponents(), true)) {
                $blockingReasons[] = sprintf('Backend migration command pilot components must include "%s".', $pilotComponent);
            }
        }
        $checks[] = 'pilot_components';

        foreach ([ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT, ObjectFieldPackName::TITLE] as $baselinePack) {
            if (!in_array($baselinePack, $packet->codexInstructions(), true) && !in_array($baselinePack, $packet->backendArtifacts(), true)) {
                $blockingReasons[] = sprintf('Backend migration command must mention baseline pack "%s".', $baselinePack);
            }
        }
        $checks[] = 'baseline_objecting_packs';

        foreach ([ObjectFieldPackName::STATE, ObjectFieldPackName::SOURCE, ObjectFieldPackName::FINGERPRINT, ObjectFieldPackName::SCOPE] as $systemicPack) {
            if (!in_array($systemicPack, $packet->codexInstructions(), true) && !in_array($systemicPack, $packet->backendArtifacts(), true)) {
                $blockingReasons[] = sprintf('Backend migration command must mention systemic pack "%s".', $systemicPack);
            }
        }
        $checks[] = 'systemic_objecting_packs';

        foreach (['priority', 'visibility'] as $deferredToken) {
            if (!in_array($deferredToken, $packet->deferredTokens(), true)) {
                $blockingReasons[] = sprintf('Backend migration command deferred tokens must include "%s".', $deferredToken);
            }
        }
        $checks[] = 'deferred_tokens';

        foreach (['composer dump-autoload', 'composer test:quality'] as $requiredGate) {
            if (!in_array($requiredGate, $packet->qualityGates(), true)) {
                $blockingReasons[] = sprintf('Backend migration command quality gates must include "%s".', $requiredGate);
            }
        }
        $checks[] = 'quality_gates';

        foreach (['no full repository overwrite', 'no /src/Domain/', 'no Port and Adapter pattern', 'no Symfony 7 constraints'] as $forbiddenAction) {
            if (!in_array($forbiddenAction, $packet->forbiddenActions(), true)) {
                $blockingReasons[] = sprintf('Backend migration command forbidden actions must include "%s".', $forbiddenAction);
            }
        }
        $checks[] = 'forbidden_actions';

        if (!$packet->touchedFilesOnly()) {
            $blockingReasons[] = 'Backend migration command must require touched-files delivery.';
        }
        $checks[] = 'touched_files_only';

        if (!$packet->cumulativeForBackupOnly()) {
            $blockingReasons[] = 'Backend migration command cumulative archive must be backup/reference only.';
        }
        $checks[] = 'cumulative_backup_only';

        if (!$packet->destructiveRepositoryCleanupForbidden()) {
            $blockingReasons[] = 'Destructive repository cleanup must remain forbidden.';
        }
        $checks[] = 'destructive_repository_cleanup_forbidden';

        if (!$packet->siblingComponentsCanBeModified()) {
            $blockingReasons[] = 'Backend migration command must allow sibling component touched-file migrations.';
        }
        $checks[] = 'sibling_components_can_be_modified';

        if ($packet->objectingCanBeModified()) {
            $blockingReasons[] = 'Backend migration command must not modify Objecting; Objecting is the dependency baseline.';
        }
        $checks[] = 'objecting_locked';

        if ($packet->exposingCanBeModified()) {
            $blockingReasons[] = 'Backend migration command must not modify Exposing in the backend migration wave.';
        }
        $checks[] = 'exposing_locked';

        return new ObjectBackendMigrationCommandReport(
            packet: $packet,
            checks: $checks,
            blockingReasons: array_values(array_unique($blockingReasons)),
        );
    }
}
