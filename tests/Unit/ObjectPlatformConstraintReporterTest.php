<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Manifest\ObjectPlatformConstraintManifest;
use App\Objecting\Reporter\Release\ObjectPlatformConstraintReporter;
use App\Objecting\Surface\ObjectPackageSurface;
use PHPUnit\Framework\TestCase;

final class ObjectPlatformConstraintReporterTest extends TestCase
{
    public function testItMarksSymfony8Php84PlatformReady(): void
    {
        $manifest = new ObjectPlatformConstraintManifest(
            constraintCandidate: 'objecting_wave20_platform_constraints',
            packageName: ObjectPackageSurface::COMPOSER_PACKAGE,
            phpConstraint: '^8.4',
            symfonyConstraint: '^8.0',
            namespacePrefix: ObjectPackageSurface::NAMESPACE_PREFIX,
            bundleClass: ObjectPackageSurface::BUNDLE_CLASS,
            requiredConstraints: [
                'php' => '^8.4',
                'symfony/config' => '^8.0',
                'symfony/dependency-injection' => '^8.0',
                'symfony/http-kernel' => '^8.0',
                'symfony/uid' => '^8.0',
                'symfony/yaml' => '^8.0',
            ],
            extraSymfony: ['require' => '^8.0'],
            forbiddenConstraints: ['^7.0 || ^8.0', '^7 || ^8', '^7.0', '7.*'],
            qualityGates: ['composer test:platform-constraints', 'php tools/test/objecting_platform_constraint_check.php'],
        );

        $report = (new ObjectPlatformConstraintReporter())->report($manifest);

        self::assertTrue($report->isReady());
        self::assertSame('ready', $report->status());
        self::assertSame([], $report->blockingReasons());
        self::assertContains('php_constraint_is_84', $report->checks());
        self::assertContains('symfony_constraint_is_8_only', $report->checks());
    }

    public function testItBlocksSymfonySevenDrift(): void
    {
        $manifest = new ObjectPlatformConstraintManifest(
            constraintCandidate: 'objecting_wave20_platform_constraints',
            packageName: ObjectPackageSurface::COMPOSER_PACKAGE,
            phpConstraint: '^8.4',
            symfonyConstraint: '^8.0',
            namespacePrefix: ObjectPackageSurface::NAMESPACE_PREFIX,
            bundleClass: ObjectPackageSurface::BUNDLE_CLASS,
            requiredConstraints: [
                'php' => '^8.4',
                'symfony/config' => '^7.0 || ^8.0',
                'symfony/dependency-injection' => '^8.0',
                'symfony/http-kernel' => '^8.0',
                'symfony/uid' => '^8.0',
                'symfony/yaml' => '^8.0',
            ],
            extraSymfony: ['require' => '^7.0 || ^8.0'],
            forbiddenConstraints: ['^7.0 || ^8.0'],
            qualityGates: ['composer test:platform-constraints', 'php tools/test/objecting_platform_constraint_check.php'],
        );

        $report = (new ObjectPlatformConstraintReporter())->report($manifest);

        self::assertFalse($report->isReady());
        self::assertSame('blocked', $report->status());
        self::assertNotSame([], $report->blockingReasons());
    }
}
