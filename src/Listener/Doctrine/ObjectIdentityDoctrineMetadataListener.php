<?php

declare(strict_types=1);

namespace App\Objecting\Listener\Doctrine;

use App\Objecting\Embeddable\ObjectIdentityEmbeddable;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

final class ObjectIdentityDoctrineMetadataListener
{
    public function loadClassMetadata(LoadClassMetadataEventArgs $event): void
    {
        $this->apply($event->getClassMetadata());
    }

    /** @param ClassMetadata<object> $metadata */
    public function apply(ClassMetadata $metadata): void
    {
        $hasIdentityEmbeddable = false;
        foreach ($metadata->embeddedClasses as $embeddedClass) {
            if (ObjectIdentityEmbeddable::class === $embeddedClass->class) {
                $hasIdentityEmbeddable = true;
                break;
            }
        }

        if (!$hasIdentityEmbeddable || $metadata->isMappedSuperclass) {
            return;
        }

        $columns = $metadata->getColumnNames();
        foreach (['uuid', 'slug'] as $requiredColumn) {
            if (!in_array($requiredColumn, $columns, true)) {
                throw new \LogicException(sprintf('Objecting identity consumer %s must map the canonical %s column.', $metadata->name, $requiredColumn));
            }
        }

        $tableName = $metadata->getTableName();
        $this->addUniqueConstraint($metadata, 'uniq_'.$tableName.'_uuid', ['uuid']);
        $this->addUniqueConstraint($metadata, 'uniq_'.$tableName.'_slug', ['slug']);
    }

    /**
     * @param ClassMetadata<object> $metadata
     * @param list<string>          $columns
     */
    private function addUniqueConstraint(ClassMetadata $metadata, string $name, array $columns): void
    {
        $existing = $metadata->table['uniqueConstraints'][$name] ?? null;
        if (is_array($existing)) {
            $existingColumns = array_values($existing['columns'] ?? []);
            if ($existingColumns !== $columns) {
                throw new \LogicException(sprintf('Doctrine unique constraint %s on %s conflicts with the Objecting identity contract.', $name, $metadata->name));
            }

            return;
        }

        (new ClassMetadataBuilder($metadata))->addUniqueConstraint($columns, $name);
    }
}
