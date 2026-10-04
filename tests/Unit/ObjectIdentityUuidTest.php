<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Embeddable\ObjectIdentityEmbeddable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV7;

final class ObjectIdentityUuidTest extends TestCase
{
    public function testGeneratedObjectUuidUsesVersionSevenAndDefaultsSlugToBase32Token(): void
    {
        $identity = new ObjectIdentityEmbeddable();
        $token = $identity->getObjectUuid();
        $uuid = Uuid::fromString($token);

        self::assertInstanceOf(UuidV7::class, $uuid);
        self::assertSame(26, strlen($token));
        self::assertSame($token, $identity->getObjectSlug());
    }

    public function testObjectUuidAndSlugAreIndependentIdentifiers(): void
    {
        $identity = new ObjectIdentityEmbeddable(null, 'human-readable-slug');

        self::assertInstanceOf(UuidV7::class, Uuid::fromString($identity->getObjectUuid()));
        self::assertSame('human-readable-slug', $identity->getObjectSlug());
        self::assertNotSame($identity->getObjectUuid(), $identity->getObjectSlug());
    }

    public function testExplicitUuidIsAcceptedAndExposedAsCanonicalBase32(): void
    {
        $uuid = Uuid::v7();
        $identity = new ObjectIdentityEmbeddable($uuid->toRfc4122(), null);

        self::assertSame($uuid->toBase32(), $identity->getObjectUuid());
        self::assertSame($identity->getObjectUuid(), $identity->getObjectSlug());
    }
}
