<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Tests\Support\ObjectEmbeddableTraitUsage;
use PHPUnit\Framework\TestCase;

final class ObjectLifecycleAliasCompatibilityTest extends TestCase
{
    public function testAuditAliasesExposeModifiedFields(): void
    {
        $object = new ObjectEmbeddableTraitUsage();
        $object->touchObject(new \DateTimeImmutable('2026-05-23 10:00:00'), 'editor-1');

        self::assertSame('2026-05-23 10:00:00', $object->getModifiedAt()?->format('Y-m-d H:i:s'));
        self::assertSame('editor-1', $object->getModifiedBy());

        $object->touchModified(new \DateTimeImmutable('2026-05-23 10:05:00'), 'editor-2');

        self::assertSame('2026-05-23 10:05:00', $object->getModifiedAt()?->format('Y-m-d H:i:s'));
        self::assertSame('editor-2', $object->getModifiedBy());
    }

    public function testSoftDeleteAliasesExposeDeletedFields(): void
    {
        $object = new ObjectEmbeddableTraitUsage();
        $object->deleteObject('deleter-1', new \DateTimeImmutable('2026-05-23 11:00:00'));

        self::assertTrue($object->isObjectDeleted());
        self::assertSame('2026-05-23 11:00:00', $object->getDeletedAt()?->format('Y-m-d H:i:s'));
        self::assertSame('deleter-1', $object->getDeletedBy());

        $object->restore();

        self::assertFalse($object->isObjectDeleted());
        self::assertNull($object->getDeletedAt());
        self::assertNull($object->getDeletedBy());
    }

    public function testLockAliasesExposeLockedFields(): void
    {
        $object = new ObjectEmbeddableTraitUsage();
        $object->lock('locker-1', new \DateTimeImmutable('2026-05-23 12:00:00'));

        self::assertTrue($object->isObjectLocked());
        self::assertSame('2026-05-23 12:00:00', $object->getLockedAt()?->format('Y-m-d H:i:s'));
        self::assertSame('locker-1', $object->getLockedBy());

        $object->unlock();

        self::assertFalse($object->isObjectLocked());
        self::assertNull($object->getLockedAt());
        self::assertNull($object->getLockedBy());
    }
}
