<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectSoftDeleteEmbeddable
{
    #[ORM\Column(name: 'object_deleted', type: 'boolean')]
    private bool $objectDeleted = false;

    #[ORM\Column(name: 'object_deleted_at', type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $objectDeletedAt = null;

    #[ORM\Column(name: 'object_deleted_by', type: 'string', length: 190, nullable: true)]
    private ?string $objectDeletedBy = null;

    public function isObjectDeleted(): bool
    {
        return $this->objectDeleted;
    }

    public function getObjectDeletedAt(): ?\DateTimeImmutable
    {
        return $this->objectDeletedAt;
    }

    public function getObjectDeletedBy(): ?string
    {
        return $this->objectDeletedBy;
    }

    public function delete(?string $deletedBy = null, ?\DateTimeImmutable $deletedAt = null): void
    {
        $this->objectDeleted = true;
        $this->objectDeletedBy = $deletedBy;
        $this->objectDeletedAt = $deletedAt ?? new \DateTimeImmutable('now');
    }

    public function restore(): void
    {
        $this->objectDeleted = false;
        $this->objectDeletedBy = null;
        $this->objectDeletedAt = null;
    }
}
