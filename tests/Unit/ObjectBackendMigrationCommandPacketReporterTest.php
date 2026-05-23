<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Service\FieldPack\ObjectBackendMigrationCommandPacketReporter;
use App\Objecting\ValueObject\ObjectBackendMigrationCommandPacket;
use App\Objecting\ValueObject\ObjectBackendMigrationCommandReport;
use App\Objecting\ValueObject\ObjectFieldPackName;
use App\Objecting\ValueObject\ObjectPackageSurface;
use PHPUnit\Framework\TestCase;

final class ObjectBackendMigrationCommandPacketReporterTest extends TestCase
{
    public function testItMarksCanonicalBackendMigrationCommandReady(): void
    {
        $packet = new ObjectBackendMigrationCommandPacket(
            name: 'objecting_backend_migration_wave1',
            packageName: ObjectPackageSurface::COMPOSER_PACKAGE,
            sourceAudit: 'workspace-objecting-field-pack-audit.md',
            targetComponents: ['Addressing', 'Taxating', 'Paging'],
            pilotComponents: ['Addressing', 'Taxating'],
            objectingArtifacts: ['resources/consumer/object-backend-migration-command.example.yaml'],
            backendArtifacts: ['resources/objecting/<BusinessStem>/object-field-packs.yaml'],
            codexInstructions: [
                ObjectFieldPackName::IDENTITY,
                ObjectFieldPackName::AUDIT,
                ObjectFieldPackName::TITLE,
                ObjectFieldPackName::STATE,
                ObjectFieldPackName::SOURCE,
                ObjectFieldPackName::FINGERPRINT,
                ObjectFieldPackName::SCOPE,
            ],
            qualityGates: ['composer dump-autoload', 'composer test:quality'],
            forbiddenActions: ['no full repository overwrite', 'no /src/Domain/', 'no Port and Adapter pattern', 'no Symfony 7 constraints'],
            deferredTokens: ['priority', 'visibility'],
        );

        $report = (new ObjectBackendMigrationCommandPacketReporter())->report($packet);

        self::assertSame(ObjectBackendMigrationCommandReport::STATUS_READY, $report->status());
        self::assertTrue($report->isReady());
        self::assertSame([], $report->blockingReasons());
        self::assertContains('pilot_components', $report->checks());
        self::assertContains('objecting_locked', $report->checks());
    }

    public function testItBlocksUnsafeBackendMigrationCommand(): void
    {
        $packet = new ObjectBackendMigrationCommandPacket(
            name: 'unsafe_backend_migration_wave',
            packageName: 'wrong/package',
            sourceAudit: 'audit.txt',
            targetComponents: ['Addressing', 'Taxating'],
            pilotComponents: ['Addressing'],
            objectingArtifacts: ['resources/consumer/object-backend-migration-command.example.yaml'],
            backendArtifacts: ['resources/objecting/<BusinessStem>/object-field-packs.yaml'],
            codexInstructions: [ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT, ObjectFieldPackName::TITLE],
            qualityGates: ['composer dump-autoload'],
            forbiddenActions: ['no full repository overwrite'],
            deferredTokens: ['priority'],
            touchedFilesOnly: false,
            cumulativeForBackupOnly: false,
            destructiveRepositoryCleanupForbidden: false,
            siblingComponentsCanBeModified: false,
            objectingCanBeModified: true,
            exposingCanBeModified: true,
        );

        $report = (new ObjectBackendMigrationCommandPacketReporter())->report($packet);

        self::assertSame(ObjectBackendMigrationCommandReport::STATUS_BLOCKED, $report->status());
        self::assertFalse($report->isReady());
        self::assertNotSame([], $report->blockingReasons());
    }
}
