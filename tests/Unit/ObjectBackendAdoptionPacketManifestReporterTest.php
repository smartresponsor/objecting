<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Manifest\ObjectBackendAdoptionPacketManifest;
use App\Objecting\Report\ObjectBackendAdoptionPacketReport;
use App\Objecting\Reporter\FieldPack\ObjectBackendAdoptionPacketManifestReporter;
use App\Objecting\Surface\ObjectPackageSurface;
use App\Objecting\ValueObject\ObjectFieldPackName;
use App\Objecting\ValueObject\ObjectTitleAliasProfileName;
use PHPUnit\Framework\TestCase;

final class ObjectBackendAdoptionPacketManifestReporterTest extends TestCase
{
    public function testItMarksCanonicalBackendAdoptionPacketReady(): void
    {
        $manifest = new ObjectBackendAdoptionPacketManifest(
            component: 'Paging',
            businessStem: 'Page',
            namespace: 'App\\Paging',
            backendProjectRoot: 'D:\\PhpstormProjects\\www\\Paging',
            packageName: ObjectPackageSurface::COMPOSER_PACKAGE,
            packageConstraint: '^1.0',
            fieldPackContractPath: 'resources/objecting/Page/object-field-packs.yaml',
            readinessManifestPath: 'resources/objecting/Page/object-backend-migration-readiness.yaml',
            adoptionManifestPath: 'resources/objecting/Page/object-backend-adoption.yaml',
            handoffManifestPath: 'resources/objecting/Page/object-backend-handoff.yaml',
            releaseClosureManifestPath: ObjectPackageSurface::RELEASE_CLOSURE_EXAMPLE,
            requiredFieldPacks: [ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT, ObjectFieldPackName::TITLE],
            qualityGates: ['composer dump-autoload', 'composer test:quality'],
            requiredComposerScripts: ['test:canon', 'test:quality'],
            packetArtifacts: [
                'resources/objecting/Page/object-field-packs.yaml',
                'resources/objecting/Page/object-backend-migration-readiness.yaml',
                'resources/objecting/Page/object-backend-adoption.yaml',
                'resources/objecting/Page/object-backend-handoff.yaml',
                ObjectPackageSurface::RELEASE_CLOSURE_EXAMPLE,
            ],
            titleAliasProfile: ObjectTitleAliasProfileName::CONTENT,
            exposingContractPath: 'contract/component/Paging/Page/manifest.yaml',
            standaloneReady: true,
        );

        $report = (new ObjectBackendAdoptionPacketManifestReporter())->report($manifest);

        self::assertSame(ObjectBackendAdoptionPacketReport::STATUS_READY, $report->status());
        self::assertTrue($report->isReady());
        self::assertSame([], $report->blockingReasons());
        self::assertContains('backend_packet_artifacts', $report->checks());
        self::assertContains('objecting_release_closure_link', $report->checks());
    }

    public function testItBlocksIncompleteBackendAdoptionPacket(): void
    {
        $manifest = new ObjectBackendAdoptionPacketManifest(
            component: 'Paging',
            businessStem: 'Page',
            namespace: 'App\\Wrong',
            backendProjectRoot: 'D:\\PhpstormProjects\\www\\Paging',
            packageName: 'wrong/package',
            packageConstraint: '^1.0',
            fieldPackContractPath: 'resources/objecting/Wrong/object-field-packs.yaml',
            readinessManifestPath: 'resources/objecting/Wrong/object-backend-migration-readiness.yaml',
            adoptionManifestPath: 'resources/objecting/Wrong/object-backend-adoption.yaml',
            handoffManifestPath: 'resources/objecting/Wrong/object-backend-handoff.yaml',
            releaseClosureManifestPath: 'resources/release/wrong.yaml',
            requiredFieldPacks: [ObjectFieldPackName::IDENTITY],
            qualityGates: ['composer dump-autoload'],
            requiredComposerScripts: ['test:canon'],
            packetArtifacts: ['resources/objecting/Wrong/object-field-packs.yaml'],
            exposingContractPath: 'contract/component/Wrong/Page/manifest.yaml',
            standaloneReady: false,
        );

        $report = (new ObjectBackendAdoptionPacketManifestReporter())->report($manifest);

        self::assertSame(ObjectBackendAdoptionPacketReport::STATUS_BLOCKED, $report->status());
        self::assertFalse($report->isReady());
        self::assertNotSame([], $report->blockingReasons());
    }
}
