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

        self::assertSame('2026-05-23 09:00:00', $audit->getCreatedAt()->format('Y-m-d H:i:s'));
        self::assertSame('vendor-1', $audit->getCreatedBy());
        self::assertNull($audit->getModifiedAt());
        self::assertNull($audit->getModifiedBy());

        $audit->touchModified(new \DateTimeImmutable('2026-05-23 10:05:00'), 'vendor-2');

        self::assertSame('2026-05-23 10:05:00', $audit->getModifiedAt()?->format('Y-m-d H:i:s'));
        self::assertSame('vendor-2', $audit->getModifiedBy());
    }
}
