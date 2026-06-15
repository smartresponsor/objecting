<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Embeddable\ObjectAuditEmbeddable;
use PHPUnit\Framework\TestCase;

final class ObjectLifecycleAuditTest extends TestCase
{
    public function testItUsesCreatedAndModifiedLifecycleVocabulary(): void
    {
        $audit = new ObjectAuditEmbeddable(
            new \DateTimeImmutable('2026-05-23 09:00:00'),
            'vendor-1',
        );

        self::assertSame('2026-05-23 09:00:00', $audit->getObjectCreatedAt()->format('Y-m-d H:i:s'));
        self::assertSame('vendor-1', $audit->getObjectCreatedBy());
        self::assertNull($audit->getObjectModifiedAt());
        self::assertNull($audit->getObjectModifiedBy());

        $audit->touchModified(new \DateTimeImmutable('2026-05-23 10:05:00'), 'vendor-2');

        self::assertSame('2026-05-23 10:05:00', $audit->getObjectModifiedAt()?->format('Y-m-d H:i:s'));
        self::assertSame('vendor-2', $audit->getObjectModifiedBy());
    }
}
