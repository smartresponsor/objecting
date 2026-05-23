<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectLockEmbeddable
{
    #[ORM\Column(name: 'object_locked_at', type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $objectLockedAt = null;

    #[ORM\Column(name: 'object_locked_by', type: 'string', length: 190, nullable: true)]
    private ?string $objectLockedBy = null;

    public function getObjectLockedAt(): ?\DateTimeImmutable
    {
        return $this->objectLockedAt;
    }

    public function getObjectLockedBy(): ?string
    {
        return $this->objectLockedBy;
    }

    public function lock(?string $objectLockedBy = null, ?\DateTimeImmutable $objectLockedAt = null): void
    {
        $this->objectLockedAt = $objectLockedAt ?? new \DateTimeImmutable('now');
        $this->objectLockedBy = $objectLockedBy;
    }

    public function unlock(): void
    {
        $this->objectLockedAt = null;
        $this->objectLockedBy = null;
    }

    public function isObjectLocked(): bool
    {
        return null !== $this->objectLockedAt;
    }
}
