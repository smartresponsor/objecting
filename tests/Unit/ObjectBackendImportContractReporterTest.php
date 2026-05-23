<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Service\FieldPack\ObjectBackendImportContractReporter;
use App\Objecting\ValueObject\ObjectBackendImportContract;
use App\Objecting\ValueObject\ObjectBackendImportReport;
use App\Objecting\ValueObject\ObjectFieldPackName;
use App\Objecting\ValueObject\ObjectPackageSurface;
use App\Objecting\ValueObject\ObjectTitleAliasProfileName;
use PHPUnit\Framework\TestCase;

final class ObjectBackendImportContractReporterTest extends TestCase
{
    public function testItMarksCanonicalBackendImportReady(): void
    {
        $contract = new ObjectBackendImportContract(
            component: 'Paging',
            businessStem: 'Page',
            namespace: 'App\\Paging',
            entityClass: 'App\\Paging\\Entity\\Page',
            backendProjectRoot: 'D:\\PhpstormProjects\\www\\Paging',
            packageName: ObjectPackageSurface::COMPOSER_PACKAGE,
            packageConstraint: '^1.0',
            adoptionPacketPath: 'resources/objecting/Page/object-backend-adoption-packet.yaml',
            fieldPackContractPath: 'resources/objecting/Page/object-field-packs.yaml',
            doctrineMappingContractPath: 'resources/objecting/Page/object-doctrine-mapping.yaml',
            schemaMirrorContractPath: 'resources/schema/Page/object-schema-mirror.yaml',
            exposingBridgeContractPath: 'resources/objecting/Page/object-exposing-bridge.yaml',
            requiredFieldPacks: [ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT, ObjectFieldPackName::TITLE],
            importArtifacts: [
                'resources/objecting/Page/object-backend-adoption-packet.yaml',
                'resources/objecting/Page/object-field-packs.yaml',
                'resources/objecting/Page/object-doctrine-mapping.yaml',
                'resources/schema/Page/object-schema-mirror.yaml',
                'resources/objecting/Page/object-exposing-bridge.yaml',
            ],
            qualityGates: ['composer dump-autoload', 'composer test:quality', 'php tools/test/objecting_backend_import_contract_check.php'],
            requiredComposerScripts: ['test:canon', 'test:quality', 'test:backend-import'],
            titleAliasProfile: ObjectTitleAliasProfileName::CONTENT,
            backendOwnsRuntime: true,
            objectingOwnsSystemFields: true,
            importInformational: true,
        );

        $report = (new ObjectBackendImportContractReporter())->report($contract);

        self::assertSame(ObjectBackendImportReport::STATUS_READY, $report->status());
        self::assertTrue($report->isReady());
        self::assertSame([], $report->blockingReasons());
        self::assertContains('backend_import_artifacts', $report->checks());
        self::assertContains('objecting_system_field_owner', $report->checks());
    }

    public function testItBlocksIncompleteBackendImport(): void
    {
        $contract = new ObjectBackendImportContract(
            component: 'Paging',
            businessStem: 'Page',
            namespace: 'App\\Wrong',
            entityClass: 'App\\Wrong\\Entity\\Page',
            backendProjectRoot: 'D:\\PhpstormProjects\\www\\Paging',
            packageName: 'wrong/package',
            packageConstraint: '^1.0',
            adoptionPacketPath: 'resources/objecting/Wrong/object-backend-adoption-packet.yaml',
            fieldPackContractPath: 'resources/objecting/Wrong/object-field-packs.yaml',
            doctrineMappingContractPath: 'resources/objecting/Wrong/object-doctrine-mapping.yaml',
            schemaMirrorContractPath: 'resources/schema/Wrong/object-schema-mirror.yaml',
            exposingBridgeContractPath: 'resources/objecting/Wrong/object-exposing-bridge.yaml',
            requiredFieldPacks: [ObjectFieldPackName::IDENTITY],
            importArtifacts: ['resources/objecting/Wrong/object-backend-adoption-packet.yaml'],
            qualityGates: ['composer dump-autoload'],
            requiredComposerScripts: ['test:canon'],
            backendOwnsRuntime: false,
            objectingOwnsSystemFields: false,
            importInformational: false,
        );

        $report = (new ObjectBackendImportContractReporter())->report($contract);

        self::assertSame(ObjectBackendImportReport::STATUS_BLOCKED, $report->status());
        self::assertFalse($report->isReady());
        self::assertNotSame([], $report->blockingReasons());
    }
}
