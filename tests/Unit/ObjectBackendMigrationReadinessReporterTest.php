<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Contract\ObjectFieldPackConsumerContract;
use App\Objecting\Report\ObjectBackendMigrationReadinessReport;
use App\Objecting\Reporter\FieldPack\ObjectBackendMigrationReadinessReporter;
use App\Objecting\ValueObject\ObjectFieldPackName;
use App\Objecting\ValueObject\ObjectFieldPackProfileName;
use App\Objecting\ValueObject\ObjectTitleAliasProfileName;
use PHPUnit\Framework\TestCase;

final class ObjectBackendMigrationReadinessReporterTest extends TestCase
{
    public function testContentProfileContractIsReadyForBackendMigration(): void
    {
        $report = (new ObjectBackendMigrationReadinessReporter())->report(new ObjectFieldPackConsumerContract(
            component: 'Paging',
            businessStem: 'Page',
            entityClass: 'App\\Paging\\Entity\\Page',
            fieldPacks: [],
            fieldPackProfile: ObjectFieldPackProfileName::CONTENT,
            titleAliasProfile: ObjectTitleAliasProfileName::CONTENT,
        ));

        self::assertSame(ObjectBackendMigrationReadinessReport::STATUS_READY, $report->status());
        self::assertTrue($report->isReady());
        self::assertSame([], $report->blockingReasons());
        self::assertContains(ObjectFieldPackName::TITLE, $report->contract()->effectiveFieldPacks());
    }

    public function testContractWithoutObjectTitleIsBlocked(): void
    {
        $report = (new ObjectBackendMigrationReadinessReporter())->report(new ObjectFieldPackConsumerContract(
            component: 'Pricing',
            businessStem: 'Price',
            entityClass: 'App\\Pricing\\Entity\\Price',
            fieldPacks: [ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT],
        ));

        self::assertSame(ObjectBackendMigrationReadinessReport::STATUS_BLOCKED, $report->status());
        self::assertSame([ObjectFieldPackName::TITLE], $report->missingBaselineFieldPacks());
    }

    public function testEntityNamespaceMustBelongToConsumerComponent(): void
    {
        $report = (new ObjectBackendMigrationReadinessReporter())->report(new ObjectFieldPackConsumerContract(
            component: 'Paging',
            businessStem: 'Page',
            entityClass: 'App\\Other\\Entity\\Page',
            fieldPacks: ObjectBackendMigrationReadinessReporter::REQUIRED_BASELINE_FIELD_PACKS,
        ));

        self::assertSame(ObjectBackendMigrationReadinessReport::STATUS_BLOCKED, $report->status());
        self::assertNotSame([], $report->blockingReasons());
    }
}
