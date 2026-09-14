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
            'created_at',
            'created_by',
            'deleted',
            'deleted_at',
            'deleted_by',
            'etag',
            'first_title',
            'id',
            'last_title',
            'locked_at',
            'locked_by',
            'middle_title',
            'modified_at',
            'modified_by',
            'published',
            'published_at',
            'slug',
            'uuid',
            'version',
        ];
        $actualColumns = array_values(array_map(
            static fn (\Doctrine\ORM\Mapping\FieldMapping $mapping): string => $mapping->columnName,
            $metadata->fieldMappings,
        ));
        sort($actualColumns);

        self::assertSame($expectedColumns, $actualColumns);
        self::assertSame('datetime_immutable', $metadata->getFieldMapping('objectAudit.createdAt')['type']);
        self::assertFalse($metadata->getFieldMapping('objectAudit.createdAt')['nullable'] ?? false);
        self::assertTrue($metadata->getFieldMapping('objectAudit.modifiedAt')['nullable'] ?? false);
        self::assertSame(190, $metadata->getFieldMapping('objectAudit.createdBy')['length']);
        self::assertSame('boolean', $metadata->getFieldMapping('objectSoftDelete.deleted')['type']);
        self::assertSame('integer', $metadata->getFieldMapping('objectVersion.version')['type']);
        self::assertSame('binary', $metadata->getFieldMapping('objectIdentity.uuid')['type']);
        self::assertSame(16, $metadata->getFieldMapping('objectIdentity.uuid')['length']);
        self::assertFalse($metadata->getFieldMapping('objectIdentity.uuid')['nullable'] ?? false);
        self::assertSame(190, $metadata->getFieldMapping('objectIdentity.slug')['length']);
        self::assertFalse($metadata->getFieldMapping('objectIdentity.slug')['nullable'] ?? false);
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
