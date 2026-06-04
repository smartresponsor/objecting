<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Contract\ObjectSchemaMirrorContract;
use App\Objecting\Report\ObjectSchemaMirrorReport;
use App\Objecting\Reporter\Schema\ObjectSchemaMirrorContractReporter;
use App\Objecting\ValueObject\ObjectFieldPackName;
use PHPUnit\Framework\TestCase;

final class ObjectSchemaMirrorContractReporterTest extends TestCase
{
    public function testItMarksCanonicalSchemaMirrorReady(): void
    {
        $contract = new ObjectSchemaMirrorContract(
            component: 'Paging',
            businessStem: 'Page',
            namespace: 'App\\Paging',
            entityClass: 'App\\Paging\\Entity\\Page',
            tableName: 'page',
            fieldPackContractPath: 'resources/objecting/Page/object-field-packs.yaml',
            doctrineMappingContractPath: 'resources/objecting/Page/object-doctrine-mapping.yaml',
            backendSchemaMirrorPath: 'resources/schema/Page/object-schema-mirror.yaml',
            exposingSchemaMirrorPath: 'contract/component/Paging/Page/page.db-schema.yaml',
            requiredFieldPacks: [ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT, ObjectFieldPackName::TITLE],
            objectColumnNames: ['object_uuid', 'object_created_at', 'object_first_title'],
            backendColumnNames: ['id', 'page_status', 'page_sort_order'],
        );

        $report = (new ObjectSchemaMirrorContractReporter())->report($contract);

        self::assertSame(ObjectSchemaMirrorReport::STATUS_READY, $report->status());
        self::assertTrue($report->isReady());
        self::assertSame([], $report->blockingReasons());
        self::assertContains('schema_mirror_exposing_ownership', $report->checks());
    }

    public function testItBlocksBrokenSchemaMirror(): void
    {
        $contract = new ObjectSchemaMirrorContract(
            component: 'Paging',
            businessStem: 'Page',
            namespace: 'App\\Wrong',
            entityClass: 'App\\Wrong\\Entity\\WrongPage',
            tableName: 'wrong_page',
            fieldPackContractPath: 'resources/objecting/Wrong/object-field-packs.yaml',
            doctrineMappingContractPath: 'resources/objecting/Wrong/object-doctrine-mapping.yaml',
            backendSchemaMirrorPath: 'schema/Page/object-schema-mirror.yaml',
            exposingSchemaMirrorPath: 'api/Paging/Page/page.db-schema.yaml',
            requiredFieldPacks: [ObjectFieldPackName::IDENTITY],
            objectColumnNames: ['uuid'],
            backendColumnNames: ['object_business_field'],
            backendOwnsMigrations: false,
            exposingOwnsMirror: false,
            objectingOwnsSystemColumns: false,
            schemaMirrorInformational: false,
        );

        $report = (new ObjectSchemaMirrorContractReporter())->report($contract);

        self::assertSame(ObjectSchemaMirrorReport::STATUS_BLOCKED, $report->status());
        self::assertFalse($report->isReady());
        self::assertNotSame([], $report->blockingReasons());
    }
}
