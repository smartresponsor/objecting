<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Embeddable\ObjectIdentityEmbeddable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV7;

final class ObjectIdentityUuidTest extends TestCase
{
    public function testGeneratedObjectUuidUsesVersionSeven(): void
    {
        $identity = new ObjectIdentityEmbeddable();
        $uuid = Uuid::fromString($identity->getObjectUuid());

        self::assertInstanceOf(UuidV7::class, $uuid);
        self::assertSame(36, strlen($identity->getObjectUuid()));
    }

    public function testObjectUuidAndSlugAreIndependentIdentifiers(): void
    {
        $identity = new ObjectIdentityEmbeddable(null, 'human-readable-slug');

        self::assertInstanceOf(UuidV7::class, Uuid::fromString($identity->getObjectUuid()));
        self::assertSame('human-readable-slug', $identity->getObjectSlug());
        self::assertNotSame($identity->getObjectUuid(), $identity->getObjectSlug());
    }

    public function testExplicitUuidIsPreservedForHydrationAndImports(): void
    {
        $uuid = Uuid::v7()->toRfc4122();
        $identity = new ObjectIdentityEmbeddable($uuid, null);

        self::assertSame($uuid, $identity->getObjectUuid());
    }
}
