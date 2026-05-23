<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Service\FieldPack\ObjectBackendHandoffManifestReporter;
use App\Objecting\ValueObject\ObjectBackendHandoffManifest;
use PHPUnit\Framework\TestCase;

final class ObjectBackendHandoffManifestReporterTest extends TestCase
{
    public function testItMarksCanonicalBackendHandoffReady(): void
    {
        $manifest = new ObjectBackendHandoffManifest(
            component: 'Paging',
            businessStem: 'Page',
            namespace: 'App\\Paging',
            packageName: 'smart-responsor/objecting',
            packageConstraint: '^1.0',
            backendProjectRoot: 'D:\\PhpstormProjects\\www\\Paging',
            adoptionManifestPath: 'resources/objecting/Page/object-backend-adoption.yaml',
            readinessManifestPath: 'resources/objecting/Page/object-backend-migration-readiness.yaml',
            qualityGates: ['composer dump-autoload', 'composer test:quality', 'php tools/test/objecting_backend_adoption_manifest_check.php'],
            requiredComposerScripts: ['test:canon', 'test:quality'],
            exposingContractPath: 'contract/component/Paging/Page/manifest.yaml',
            standaloneReady: true,
        );

        $report = (new ObjectBackendHandoffManifestReporter())->report($manifest);

        self::assertTrue($report->isReady());
        self::assertSame('ready', $report->status());
        self::assertSame([], $report->blockingReasons());
        self::assertContains('backend_quality_gates', $report->checks());
    }

    public function testItBlocksInvalidBackendHandoff(): void
    {
        $manifest = new ObjectBackendHandoffManifest(
            component: 'Paging',
            businessStem: 'Page',
            namespace: 'App\\Wrong',
            packageName: 'other/package',
            packageConstraint: '^1.0',
            backendProjectRoot: 'D:\\PhpstormProjects\\www\\Paging',
            adoptionManifestPath: 'resources/objecting/Wrong/object-backend-adoption.yaml',
            readinessManifestPath: 'resources/objecting/Wrong/object-backend-migration-readiness.yaml',
            qualityGates: ['composer dump-autoload'],
            requiredComposerScripts: ['test:canon'],
            exposingContractPath: 'contract/component/Wrong/Page/manifest.yaml',
            standaloneReady: false,
        );

        $report = (new ObjectBackendHandoffManifestReporter())->report($manifest);

        self::assertFalse($report->isReady());
        self::assertSame('blocked', $report->status());
        self::assertNotSame([], $report->blockingReasons());
    }
}
