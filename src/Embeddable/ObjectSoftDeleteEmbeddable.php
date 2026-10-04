<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectSoftDeleteEmbeddable
{
    #[ORM\Column(name: 'deleted', type: 'boolean')]
    private bool $deleted = false;

    #[ORM\Column(name: 'deleted_at', type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\Column(name: 'deleted_by', type: 'string', length: 190, nullable: true)]
    private ?string $deletedBy = null;

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function getDeletedBy(): ?string
    {
        return $this->deletedBy;
    }

    public function delete(?string $deletedBy = null, ?\DateTimeImmutable $deletedAt = null): void
    {
        $this->deleted = true;
        $this->deletedBy = $deletedBy;
        $this->deletedAt = $deletedAt ?? new \DateTimeImmutable('now');
    }

    public function restore(): void
    {
        $this->deleted = false;
        $this->deletedBy = null;
        $this->deletedAt = null;
    }
}
