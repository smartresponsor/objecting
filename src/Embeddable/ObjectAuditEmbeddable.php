<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectAuditEmbeddable
{
    #[ORM\Column(name: 'object_created_at', type: 'datetime_immutable')]
    private \DateTimeImmutable $objectCreatedAt;

    #[ORM\Column(name: 'object_updated_at', type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $objectUpdatedAt = null;

    #[ORM\Column(name: 'object_created_by', type: 'string', length: 190, nullable: true)]
    private ?string $objectCreatedBy = null;

    #[ORM\Column(name: 'object_updated_by', type: 'string', length: 190, nullable: true)]
    private ?string $objectUpdatedBy = null;

    public function __construct(?\DateTimeImmutable $createdAt = null, ?string $createdBy = null)
    {
        $this->objectCreatedAt = $createdAt ?? new \DateTimeImmutable('now');
        $this->objectCreatedBy = $createdBy;
    }

    public function getObjectCreatedAt(): \DateTimeImmutable
    {
        return $this->objectCreatedAt;
    }

    public function getObjectUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->objectUpdatedAt;
    }

    public function getObjectCreatedBy(): ?string
    {
        return $this->objectCreatedBy;
    }

    public function getObjectUpdatedBy(): ?string
    {
        return $this->objectUpdatedBy;
    }

    public function getModifiedAt(): ?\DateTimeImmutable
    {
        return $this->getObjectUpdatedAt();
    }

    public function getModifiedBy(): ?string
    {
        return $this->getObjectUpdatedBy();
    }

    public function touch(?\DateTimeImmutable $updatedAt = null, ?string $updatedBy = null): void
    {
        $this->objectUpdatedAt = $updatedAt ?? new \DateTimeImmutable('now');
        $this->objectUpdatedBy = $updatedBy;
    }
}
