<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use App\Objecting\Embeddable\ObjectSoftDeleteEmbeddable;
use Doctrine\ORM\Mapping as ORM;

trait ObjectSoftDeleteEmbeddableTrait
{
    #[ORM\Embedded(class: ObjectSoftDeleteEmbeddable::class, columnPrefix: false)]
    private ObjectSoftDeleteEmbeddable $objectSoftDelete;

    protected function initializeObjectSoftDelete(): void
    {
        $this->objectSoftDelete = new ObjectSoftDeleteEmbeddable();
    }

    private function objectSoftDeleteEmbeddable(): ObjectSoftDeleteEmbeddable
    {
        if (!isset($this->objectSoftDelete)) {
            $this->objectSoftDelete = new ObjectSoftDeleteEmbeddable();
        }

        return $this->objectSoftDelete;
    }

    public function isObjectDeleted(): bool
    {
        return $this->objectSoftDeleteEmbeddable()->isObjectDeleted();
    }

    public function getObjectDeletedAt(): ?\DateTimeImmutable
    {
        return $this->objectSoftDeleteEmbeddable()->getObjectDeletedAt();
    }

    public function getObjectDeletedBy(): ?string
    {
        return $this->objectSoftDeleteEmbeddable()->getObjectDeletedBy();
    }

    public function deleteObject(?string $deletedBy = null, ?\DateTimeImmutable $deletedAt = null): void
    {
        $this->objectSoftDeleteEmbeddable()->delete($deletedBy, $deletedAt);
    }

    public function restoreObject(): void
    {
        $this->objectSoftDeleteEmbeddable()->restore();
    }
}
