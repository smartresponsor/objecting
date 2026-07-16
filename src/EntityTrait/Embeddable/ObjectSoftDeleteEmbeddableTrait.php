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

    public function isDeleted(): bool
    {
        return $this->objectSoftDeleteEmbeddable()->isDeleted();
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->objectSoftDeleteEmbeddable()->getDeletedAt();
    }

    public function getDeletedBy(): ?string
    {
        return $this->objectSoftDeleteEmbeddable()->getDeletedBy();
    }

    public function delete(?string $deletedBy = null, ?\DateTimeImmutable $deletedAt = null): void
    {
        $this->objectSoftDeleteEmbeddable()->delete($deletedBy, $deletedAt);
    }

    public function restore(): void
    {
        $this->objectSoftDeleteEmbeddable()->restore();
    }
}
