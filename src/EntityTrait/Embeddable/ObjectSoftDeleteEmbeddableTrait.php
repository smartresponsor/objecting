<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use Doctrine\ORM\Mapping as ORM;

trait ObjectSoftDeleteEmbeddableTrait
{
    #[ORM\Embedded(class: \App\Objecting\Embeddable\ObjectSoftDeleteEmbeddable::class, columnPrefix: false)]
    private \App\Objecting\Embeddable\ObjectSoftDeleteEmbeddable $objectSoftDelete;

    protected function initializeObjectSoftDelete(): void
    {
        $this->objectSoftDelete = new \App\Objecting\Embeddable\ObjectSoftDeleteEmbeddable();
    }

    private function objectSoftDeleteEmbeddable(): \App\Objecting\Embeddable\ObjectSoftDeleteEmbeddable
    {
        if (!isset($this->objectSoftDelete)) {
            $this->objectSoftDelete = new \App\Objecting\Embeddable\ObjectSoftDeleteEmbeddable();
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

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->objectSoftDeleteEmbeddable()->getDeletedAt();
    }

    public function getDeletedBy(): ?string
    {
        return $this->objectSoftDeleteEmbeddable()->getDeletedBy();
    }

    public function deleteObject(?string $deletedBy = null, ?\DateTimeImmutable $deletedAt = null): void
    {
        $this->objectSoftDeleteEmbeddable()->delete($deletedBy, $deletedAt);
    }

    public function delete(?string $deletedBy = null, ?\DateTimeImmutable $deletedAt = null): void
    {
        $this->deleteObject($deletedBy, $deletedAt);
    }

    public function restoreObject(): void
    {
        $this->objectSoftDeleteEmbeddable()->restore();
    }

    public function restore(): void
    {
        $this->restoreObject();
    }
}
