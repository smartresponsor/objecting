<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Service\FieldPack\ObjectBackendAdoptionManifestReporter;
use App\Objecting\ValueObject\ObjectBackendAdoptionManifest;
use App\Objecting\ValueObject\ObjectBackendAdoptionReport;
use App\Objecting\ValueObject\ObjectFieldPackName;
use App\Objecting\ValueObject\ObjectFieldPackProfileName;
use App\Objecting\ValueObject\ObjectTitleAliasProfileName;
use PHPUnit\Framework\TestCase;

final class ObjectBackendAdoptionManifestReporterTest extends TestCase
{
    public function testBackendAdoptionManifestCanBeReady(): void
    {
        $manifest = new ObjectBackendAdoptionManifest(
            component: 'Paging',
            businessStem: 'Page',
            namespace: 'App\\Paging',
            entityClass: 'App\\Paging\\Entity\\Page',
            tableName: 'page',
            fieldPacks: [],
            effectiveFieldPacks: [
                ObjectFieldPackName::IDENTITY,
                ObjectFieldPackName::AUDIT,
                ObjectFieldPackName::TITLE,
                ObjectFieldPackName::PUBLICATION,
                ObjectFieldPackName::VERSION,
            ],
            fieldPackProfile: ObjectFieldPackProfileName::CONTENT,
            titleAliasProfile: ObjectTitleAliasProfileName::CONTENT,
            exposingContractPath: 'contract/component/Paging/Page/manifest.yaml',
        );

        $report = (new ObjectBackendAdoptionManifestReporter())->report($manifest);

        self::assertSame(ObjectBackendAdoptionReport::STATUS_READY, $report->status());
        self::assertTrue($report->isReady());
        self::assertSame([], $report->blockingReasons());
    }

    public function testBackendAdoptionManifestBlocksWrongTablePrefix(): void
    {
        $manifest = new ObjectBackendAdoptionManifest(
            component: 'Paging',
            businessStem: 'Page',
            namespace: 'App\\Paging',
            entityClass: 'App\\Paging\\Entity\\Page',
            tableName: 'content_page',
            fieldPacks: [
                ObjectFieldPackName::IDENTITY,
                ObjectFieldPackName::AUDIT,
                ObjectFieldPackName::TITLE,
            ],
            effectiveFieldPacks: [
                ObjectFieldPackName::IDENTITY,
                ObjectFieldPackName::AUDIT,
                ObjectFieldPackName::TITLE,
            ],
        );

        $report = (new ObjectBackendAdoptionManifestReporter())->report($manifest);

        self::assertSame(ObjectBackendAdoptionReport::STATUS_BLOCKED, $report->status());
        self::assertNotSame([], $report->blockingReasons());
    }
}
