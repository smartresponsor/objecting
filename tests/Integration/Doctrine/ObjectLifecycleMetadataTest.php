<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Integration\Doctrine;

use App\Objecting\Tests\Integration\Doctrine\Entity\ObjectLifecycleTestEntity;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;

final class ObjectLifecycleMetadataTest extends TestCase
{
    public function testEntityIsTheSourceOfTruthForCanonicalObjectLifecycleSchema(): void
    {
        $entityManager = $this->createEntityManager();
        $metadata = $entityManager->getClassMetadata(ObjectLifecycleTestEntity::class);

        self::assertSame('object_lifecycle_test', $metadata->getTableName());
        self::assertSame(['id'], $metadata->getIdentifierFieldNames());

        $expectedColumns = [
            'id',
            'object_created_at',
            'object_created_by',
            'object_deleted',
            'object_deleted_at',
            'object_deleted_by',
            'object_etag',
            'object_first_title',
            'object_last_title',
            'object_locked_at',
            'object_locked_by',
            'object_middle_title',
            'object_modified_at',
            'object_modified_by',
            'object_published',
            'object_published_at',
            'object_slug',
            'object_uuid',
            'object_version',
        ];
        $actualColumns = array_values(array_map(
            static fn (\Doctrine\ORM\Mapping\FieldMapping $mapping): string => $mapping->columnName,
            $metadata->fieldMappings,
        ));
        sort($actualColumns);

        self::assertSame($expectedColumns, $actualColumns);
        self::assertSame('datetime_immutable', $metadata->getFieldMapping('objectAudit.objectCreatedAt')['type']);
        self::assertFalse($metadata->getFieldMapping('objectAudit.objectCreatedAt')['nullable'] ?? false);
        self::assertTrue($metadata->getFieldMapping('objectAudit.objectModifiedAt')['nullable'] ?? false);
        self::assertSame(190, $metadata->getFieldMapping('objectAudit.objectCreatedBy')['length']);
        self::assertSame('boolean', $metadata->getFieldMapping('objectSoftDelete.objectDeleted')['type']);
        self::assertSame('integer', $metadata->getFieldMapping('objectVersion.objectVersion')['type']);
        self::assertSame('binary', $metadata->getFieldMapping('objectIdentity.objectUuid')['type']);
        self::assertSame(16, $metadata->getFieldMapping('objectIdentity.objectUuid')['length']);
        self::assertFalse($metadata->getFieldMapping('objectIdentity.objectUuid')['nullable'] ?? false);
        self::assertSame(190, $metadata->getFieldMapping('objectIdentity.objectSlug')['length']);
        self::assertFalse($metadata->getFieldMapping('objectIdentity.objectSlug')['nullable'] ?? false);
    }

    public function testEntityRoundTripsThroughDoctrineWithoutMigrationFirstSchemaDesign(): void
    {
        $entityManager = $this->createEntityManager();
        $metadata = $entityManager->getClassMetadata(ObjectLifecycleTestEntity::class);
        (new SchemaTool($entityManager))->createSchema([$metadata]);

        $createdAt = new \DateTimeImmutable('2026-07-16 15:00:00');
        $modifiedAt = new \DateTimeImmutable('2026-07-16 16:00:00');
        $publishedAt = new \DateTimeImmutable('2026-07-16 17:00:00');
        $lockedAt = new \DateTimeImmutable('2026-07-16 18:00:00');
        $deletedAt = new \DateTimeImmutable('2026-07-16 19:00:00');
        $entity = new ObjectLifecycleTestEntity('Lifecycle title', 'lifecycle-title', 'vendor-1', $createdAt);
        $entity->touchModified($modifiedAt, 'vendor-2');
        $entity->publishObject($publishedAt);
        $entity->bumpObjectVersion('etag-2');
        $entity->lock('vendor-3', $lockedAt);
        $entity->delete('vendor-4', $deletedAt);

        $entityManager->persist($entity);
        $entityManager->flush();
        $id = $entity->getId();
        self::assertNotNull($id);

        $entityManager->clear();
        $reloaded = $entityManager->find(ObjectLifecycleTestEntity::class, $id);

        self::assertInstanceOf(ObjectLifecycleTestEntity::class, $reloaded);
        self::assertSame(26, strlen($reloaded->getObjectUuid()));
        self::assertSame('Lifecycle title', $reloaded->getFirstTitle());
        self::assertSame('lifecycle-title', $reloaded->getObjectSlug());
        self::assertEquals($createdAt, $reloaded->getCreatedAt());
        self::assertSame('vendor-1', $reloaded->getCreatedBy());
        self::assertEquals($modifiedAt, $reloaded->getModifiedAt());
        self::assertSame('vendor-2', $reloaded->getModifiedBy());
        self::assertTrue($reloaded->isObjectPublished());
        self::assertEquals($publishedAt, $reloaded->getObjectPublishedAt());
        self::assertSame(2, $reloaded->getObjectVersion());
        self::assertSame('etag-2', $reloaded->getObjectEtag());
        self::assertTrue($reloaded->isLocked());
        self::assertEquals($lockedAt, $reloaded->getLockedAt());
        self::assertSame('vendor-3', $reloaded->getLockedBy());
        self::assertTrue($reloaded->isDeleted());
        self::assertEquals($deletedAt, $reloaded->getDeletedAt());
        self::assertSame('vendor-4', $reloaded->getDeletedBy());
    }

    private function createEntityManager(): EntityManager
    {
        $configuration = new Configuration();
        $configuration->setMetadataDriverImpl(new AttributeDriver([
            dirname(__DIR__, 3).'/src/Embeddable',
            __DIR__.'/Entity',
        ]));
        $configuration->setProxyDir(sys_get_temp_dir());
        $configuration->setProxyNamespace('ObjectingTestProxy');
        $configuration->setAutoGenerateProxyClasses(true);
        $configuration->enableNativeLazyObjects(true);
        $connection = DriverManager::getConnection([
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ]);

        return new EntityManager($connection, $configuration);
    }
}
