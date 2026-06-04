<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Contract\ObjectExposingBridgeContract;
use App\Objecting\Report\ObjectExposingBridgeReport;
use App\Objecting\Reporter\Exposing\ObjectExposingBridgeContractReporter;
use App\Objecting\ValueObject\ObjectFieldPackName;
use PHPUnit\Framework\TestCase;

final class ObjectExposingBridgeContractReporterTest extends TestCase
{
    public function testItMarksCanonicalExposingBridgeReady(): void
    {
        $contract = new ObjectExposingBridgeContract(
            component: 'Paging', businessStem: 'Page', namespace: 'App\\Paging', entityClass: 'App\\Paging\\Entity\\Page',
            fieldPackContractPath: 'resources/objecting/Page/object-field-packs.yaml', doctrineMappingContractPath: 'resources/objecting/Page/object-doctrine-mapping.yaml',
            schemaMirrorContractPath: 'resources/schema/Page/object-schema-mirror.yaml', backendAdoptionPacketPath: 'resources/objecting/Page/object-backend-adoption-packet.yaml',
            exposingOpenApiPath: 'contract/component/Paging/Page/page.openapi.yaml', exposingSchemaMirrorPath: 'contract/component/Paging/Page/page.db-schema.yaml',
            openApiSchemaName: 'PageResponse', titleAliasProfile: 'object_title_content',
            requiredFieldPacks: [ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT, ObjectFieldPackName::TITLE],
            exportArtifacts: ['resources/objecting/Page/object-field-packs.yaml', 'resources/objecting/Page/object-doctrine-mapping.yaml', 'resources/schema/Page/object-schema-mirror.yaml', 'resources/objecting/Page/object-backend-adoption-packet.yaml'],
        );
        $report = (new ObjectExposingBridgeContractReporter())->report($contract);
        self::assertSame(ObjectExposingBridgeReport::STATUS_READY, $report->status());
        self::assertTrue($report->isReady());
        self::assertSame([], $report->blockingReasons());
        self::assertContains('exposing_bridge_exposing_api_contract_owner', $report->checks());
    }

    public function testItBlocksBrokenExposingBridge(): void
    {
        $contract = new ObjectExposingBridgeContract(
            component: 'Paging', businessStem: 'Page', namespace: 'App\\Wrong', entityClass: 'App\\Wrong\\Entity\\WrongPage',
            fieldPackContractPath: 'resources/wrong/Page/object-field-packs.yaml', doctrineMappingContractPath: 'resources/wrong/Page/object-doctrine-mapping.yaml',
            schemaMirrorContractPath: 'schema/Page/object-schema-mirror.yaml', backendAdoptionPacketPath: 'resources/wrong/Page/object-backend-adoption-packet.yaml',
            exposingOpenApiPath: 'openapi/Page/page.openapi.yaml', exposingSchemaMirrorPath: 'schema/Page/page.db-schema.yaml', openApiSchemaName: 'WrongResponse', titleAliasProfile: 'wrong_title_profile',
            requiredFieldPacks: [ObjectFieldPackName::IDENTITY], exportArtifacts: ['resources/wrong/Page/object-field-packs.yaml'],
            backendOwnsRuntime: false, objectingOwnsFieldPacks: false, exposingOwnsApiContract: false, bridgeInformational: false,
        );
        $report = (new ObjectExposingBridgeContractReporter())->report($contract);
        self::assertSame(ObjectExposingBridgeReport::STATUS_BLOCKED, $report->status());
        self::assertFalse($report->isReady());
        self::assertNotSame([], $report->blockingReasons());
    }
}
