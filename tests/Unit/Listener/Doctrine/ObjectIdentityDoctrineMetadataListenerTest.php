<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit\Listener\Doctrine;

use App\Objecting\Embeddable\ObjectIdentityEmbeddable;
use App\Objecting\Listener\Doctrine\ObjectIdentityDoctrineMetadataListener;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHPUnit\Framework\TestCase;

final class ObjectIdentityDoctrineMetadataListenerTest extends TestCase
{
    public function testAddsDeterministicIdentityUniqueConstraints(): void
    {
        $metadata = new ClassMetadata(ObjectIdentityMetadataConsumer::class);
        $metadata->setPrimaryTable(['name' => 'example_record']);
        $metadata->mapEmbedded([
            'fieldName' => 'objectIdentity',
            'class' => ObjectIdentityEmbeddable::class,
            'columnPrefix' => false,
        ]);
        $metadata->mapField([
            'fieldName' => 'uuid',
            'columnName' => 'uuid',
            'type' => 'binary',
            'length' => 16,
        ]);
        $metadata->mapField([
            'fieldName' => 'slug',
            'columnName' => 'slug',
            'type' => 'string',
            'length' => 190,
        ]);

        $listener = new ObjectIdentityDoctrineMetadataListener();
        $listener->apply($metadata);
        $listener->apply($metadata);

        self::assertSame(
            ['columns' => ['uuid']],
            $metadata->table['uniqueConstraints']['uniq_example_record_uuid'] ?? null,
        );
        self::assertSame(
            ['columns' => ['slug']],
            $metadata->table['uniqueConstraints']['uniq_example_record_slug'] ?? null,
        );
        self::assertCount(2, $metadata->table['uniqueConstraints']);
    }

    public function testIgnoresNonObjectingEntity(): void
    {
        $metadata = new ClassMetadata(ObjectNonIdentityMetadataConsumer::class);
        $metadata->setPrimaryTable(['name' => 'plain_record']);
        $metadata->mapField([
            'fieldName' => 'uuid',
            'columnName' => 'uuid',
            'type' => 'binary',
            'length' => 16,
        ]);
        $metadata->mapField([
            'fieldName' => 'slug',
            'columnName' => 'slug',
            'type' => 'string',
            'length' => 190,
        ]);

        (new ObjectIdentityDoctrineMetadataListener())->apply($metadata);

        self::assertArrayNotHasKey('uniqueConstraints', $metadata->table);
    }
}

final class ObjectIdentityMetadataConsumer
{
}

final class ObjectNonIdentityMetadataConsumer
{
}
