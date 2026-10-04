<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Embeddable\ObjectAuditEmbeddable;
use App\Objecting\Embeddable\ObjectLockEmbeddable;
use App\Objecting\Embeddable\ObjectSoftDeleteEmbeddable;
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

    public function testItRejectsModificationBeforeCreationWithoutPartialMutation(): void
    {
        $audit = new ObjectAuditEmbeddable(
            new \DateTimeImmutable('2026-07-16 10:00:00'),
            'vendor-1',
        );

        try {
            $audit->touchModified(new \DateTimeImmutable('2026-07-16 09:59:59'), 'vendor-2');
            self::fail('Expected modification-before-creation rejection.');
        } catch (\InvalidArgumentException $exception) {
            self::assertSame(
                'Object modification timestamp cannot precede creation timestamp.',
                $exception->getMessage(),
            );
        }

        self::assertNull($audit->getModifiedAt());
        self::assertNull($audit->getModifiedBy());
        self::assertSame('vendor-1', $audit->getCreatedBy());
    }

    public function testItAllowsCreationTimestampAsModificationBoundary(): void
    {
        $createdAt = new \DateTimeImmutable('2026-07-16 10:00:00');
        $audit = new ObjectAuditEmbeddable($createdAt, 'vendor-1');

        $audit->touchModified($createdAt, 'vendor-2');

        self::assertSame($createdAt, $audit->getModifiedAt());
        self::assertSame('vendor-2', $audit->getModifiedBy());
        self::assertSame($createdAt, $audit->getCreatedAt());
        self::assertSame('vendor-1', $audit->getCreatedBy());
    }

    public function testSoftDeleteTransitionsRemainCoherentAndRepeatedDeleteReplacesAttribution(): void
    {
        $softDelete = new ObjectSoftDeleteEmbeddable();
        self::assertFalse($softDelete->isDeleted());
        self::assertNull($softDelete->getDeletedAt());
        self::assertNull($softDelete->getDeletedBy());

        $softDelete->delete('vendor-1', new \DateTimeImmutable('2026-07-16 11:00:00'));
        $replacementAt = new \DateTimeImmutable('2026-07-16 12:00:00');
        $softDelete->delete('vendor-2', $replacementAt);

        self::assertTrue($softDelete->isDeleted());
        self::assertSame($replacementAt, $softDelete->getDeletedAt());
        self::assertSame('vendor-2', $softDelete->getDeletedBy());

        $softDelete->restore();
        self::assertFalse($softDelete->isDeleted());
        self::assertNull($softDelete->getDeletedAt());
        self::assertNull($softDelete->getDeletedBy());
    }

    public function testLockTransitionsRemainCoherentAndRepeatedLockReplacesAttribution(): void
    {
        $lock = new ObjectLockEmbeddable();
        self::assertFalse($lock->isLocked());
        self::assertNull($lock->getLockedAt());
        self::assertNull($lock->getLockedBy());

        $lock->lock('vendor-1', new \DateTimeImmutable('2026-07-16 13:00:00'));
        $replacementAt = new \DateTimeImmutable('2026-07-16 14:00:00');
        $lock->lock('vendor-2', $replacementAt);

        self::assertTrue($lock->isLocked());
        self::assertSame($replacementAt, $lock->getLockedAt());
        self::assertSame('vendor-2', $lock->getLockedBy());

        $lock->unlock();
        self::assertFalse($lock->isLocked());
        self::assertNull($lock->getLockedAt());
        self::assertNull($lock->getLockedBy());
    }
}
