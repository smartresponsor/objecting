<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Service\Doctrine\ObjectDoctrineMappingContractReporter;
use App\Objecting\ValueObject\ObjectDoctrineMappingContract;
use App\Objecting\ValueObject\ObjectDoctrineMappingReport;
use App\Objecting\ValueObject\ObjectFieldPackName;
use PHPUnit\Framework\TestCase;

final class ObjectDoctrineMappingContractReporterTest extends TestCase
{
    public function testItMarksCanonicalDoctrineMappingReady(): void
    {
        $contract = new ObjectDoctrineMappingContract(
            component: 'Paging',
            businessStem: 'Page',
            namespace: 'App\\Paging',
            entityClass: 'App\\Paging\\Entity\\Page',
            tableName: 'page',
            fieldPackContractPath: 'resources/objecting/Page/object-field-packs.yaml',
            requiredFieldPacks: [ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT, ObjectFieldPackName::TITLE],
            embeddableClasses: [
                'App\\Objecting\\Embeddable\\ObjectIdentityEmbeddable',
                'App\\Objecting\\Embeddable\\ObjectAuditEmbeddable',
                'App\\Objecting\\Embeddable\\ObjectTitleEmbeddable',
            ],
            embeddedTraitClasses: [
                'App\\Objecting\\EntityTrait\\Embeddable\\ObjectIdentityEmbeddableTrait',
                'App\\Objecting\\EntityTrait\\Embeddable\\ObjectAuditEmbeddableTrait',
                'App\\Objecting\\EntityTrait\\Embeddable\\ObjectTitleEmbeddableTrait',
            ],
            columnNames: ['object_uuid', 'object_created_at', 'object_first_title'],
        );

        $report = (new ObjectDoctrineMappingContractReporter())->report($contract);

        self::assertSame(ObjectDoctrineMappingReport::STATUS_READY, $report->status());
        self::assertTrue($report->isReady());
        self::assertSame([], $report->blockingReasons());
        self::assertContains('backend_migration_ownership', $report->checks());
    }

    public function testItBlocksBrokenDoctrineMapping(): void
    {
        $contract = new ObjectDoctrineMappingContract(
            component: 'Paging',
            businessStem: 'Page',
            namespace: 'App\\Wrong',
            entityClass: 'App\\Wrong\\Entity\\WrongPage',
            tableName: 'wrong_page',
            fieldPackContractPath: 'resources/objecting/Wrong/object-field-packs.yaml',
            requiredFieldPacks: [ObjectFieldPackName::IDENTITY],
            embeddableClasses: ['App\\Wrong\\Embeddable\\IdentityEmbeddable'],
            embeddedTraitClasses: ['App\\Wrong\\Trait\\IdentityTrait'],
            columnNames: ['uuid'],
            columnPrefixFalse: false,
            backendOwnsMigrations: false,
        );

        $report = (new ObjectDoctrineMappingContractReporter())->report($contract);

        self::assertSame(ObjectDoctrineMappingReport::STATUS_BLOCKED, $report->status());
        self::assertFalse($report->isReady());
        self::assertNotSame([], $report->blockingReasons());
    }
}
