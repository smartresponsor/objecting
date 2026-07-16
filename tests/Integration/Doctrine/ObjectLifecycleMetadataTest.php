<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Integration\Doctrine;

use App\Objecting\Tests\Integration\Doctrine\Entity\ObjectLifecycleTestEntity;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use PHPUnit\Framework\TestCase;

final class ObjectLifecycleMetadataTest extends TestCase
{
    public function testEntityIsTheSourceOfTruthForCanonicalObjectLifecycleSchema(): void
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
        $entityManager = new EntityManager($connection, $configuration);
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
    }
}
