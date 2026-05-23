<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Service\FieldPack\ObjectBackendCloneCleanupContractReporter;
use App\Objecting\ValueObject\ObjectBackendCloneCleanupContract;
use App\Objecting\ValueObject\ObjectBackendCloneCleanupReport;
use App\Objecting\ValueObject\ObjectFieldPackName;
use App\Objecting\ValueObject\ObjectPackageSurface;
use PHPUnit\Framework\TestCase;

final class ObjectBackendCloneCleanupContractReporterTest extends TestCase
{
    public function testItMarksCanonicalBackendCloneCleanupReady(): void
    {
        $contract = new ObjectBackendCloneCleanupContract(
            component: 'Addressing',
            businessStem: 'Address',
            namespace: 'App\\Addressing',
            packageName: ObjectPackageSurface::COMPOSER_PACKAGE,
            cloneFiles: ['src/EntityTrait/ObjectTrait.php', 'src/EntityTrait/ObjectAuditTrait.php'],
            replacementFieldPacks: [ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT, ObjectFieldPackName::TITLE],
            cleanupArtifacts: ['src/Entity/AddressEntity.php', 'resources/objecting/Address/object-field-packs.yaml'],
            qualityGates: ['composer dump-autoload', 'composer test:quality'],
            requiredComposerScripts: ['test:canon', 'test:quality'],
            touchedFilesOnly: true,
            cumulativeForBackupOnly: true,
            backendOwnsRuntime: true,
            objectingOwnsSystemFields: true,
            destructiveRepositoryCleanupForbidden: true,
        );

        $report = (new ObjectBackendCloneCleanupContractReporter())->report($contract);

        self::assertSame(ObjectBackendCloneCleanupReport::STATUS_READY, $report->status());
        self::assertTrue($report->isReady());
        self::assertSame([], $report->blockingReasons());
        self::assertContains('object_clone_surfaces', $report->checks());
        self::assertContains('destructive_repository_cleanup_forbidden', $report->checks());
    }

    public function testItBlocksUnsafeBackendCloneCleanup(): void
    {
        $contract = new ObjectBackendCloneCleanupContract(
            component: 'Addressing',
            businessStem: 'Address',
            namespace: 'App\\Addressing',
            packageName: 'wrong/package',
            cloneFiles: ['src/EntityTrait/ObjectTrait.php'],
            replacementFieldPacks: [ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT, ObjectFieldPackName::TITLE],
            cleanupArtifacts: ['src/Entity/AddressEntity.php'],
            qualityGates: ['composer dump-autoload'],
            requiredComposerScripts: ['test:canon'],
            touchedFilesOnly: false,
            cumulativeForBackupOnly: false,
            backendOwnsRuntime: false,
            objectingOwnsSystemFields: false,
            destructiveRepositoryCleanupForbidden: false,
        );

        $report = (new ObjectBackendCloneCleanupContractReporter())->report($contract);

        self::assertSame(ObjectBackendCloneCleanupReport::STATUS_BLOCKED, $report->status());
        self::assertFalse($report->isReady());
        self::assertNotSame([], $report->blockingReasons());
    }
}
