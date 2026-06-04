<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Surface\ObjectPackageSurface;
use PHPUnit\Framework\TestCase;

final class ObjectPackageSurfaceTest extends TestCase
{
    public function testItExposesCanonicalPackageSurface(): void
    {
        self::assertSame('objecting/object', ObjectPackageSurface::COMPOSER_PACKAGE);
        self::assertSame('App\\Objecting\\', ObjectPackageSurface::NAMESPACE_PREFIX);
        self::assertSame('objecting', ObjectPackageSurface::EXTENSION_ALIAS);
        self::assertSame('config/services.yaml', ObjectPackageSurface::SERVICE_CONFIG);
        self::assertSame('resources/consumer/object-backend-migration-readiness.example.yaml', ObjectPackageSurface::BACKEND_MIGRATION_READINESS_EXAMPLE);
        self::assertSame('resources/consumer/object-backend-adoption.example.yaml', ObjectPackageSurface::BACKEND_ADOPTION_EXAMPLE);
        self::assertSame('resources/consumer/object-backend-handoff.example.yaml', ObjectPackageSurface::BACKEND_HANDOFF_EXAMPLE);
        self::assertSame('resources/consumer/object-backend-adoption-packet.example.yaml', ObjectPackageSurface::BACKEND_ADOPTION_PACKET_EXAMPLE);
        self::assertSame('resources/consumer/object-schema-mirror.example.yaml', ObjectPackageSurface::SCHEMA_MIRROR_EXAMPLE);
        self::assertSame('resources/release/objecting-release-closure.example.yaml', ObjectPackageSurface::RELEASE_CLOSURE_EXAMPLE);
        self::assertSame('resources/release/objecting-rc-stabilization.example.yaml', ObjectPackageSurface::RC_STABILIZATION_EXAMPLE);
        self::assertSame('resources/consumer/object-backend-migration-command.example.yaml', ObjectPackageSurface::BACKEND_MIGRATION_COMMAND_EXAMPLE);
    }

    public function testAllSurfaceValuesAreNonEmptyStrings(): void
    {
        foreach (ObjectPackageSurface::all() as $key => $value) {
            self::assertNotSame('', $key);
            self::assertNotSame('', $value, $key);
        }
    }
}
