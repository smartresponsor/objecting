<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectLockEmbeddable
{
    #[ORM\Column(name: 'locked_at', type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $lockedAt = null;

    #[ORM\Column(name: 'locked_by', type: 'string', length: 190, nullable: true)]
    private ?string $lockedBy = null;

    public function getLockedAt(): ?\DateTimeImmutable
    {
        return $this->lockedAt;
    }

    public function getLockedBy(): ?string
    {
        return $this->lockedBy;
    }

    public function lock(?string $objectLockedBy = null, ?\DateTimeImmutable $objectLockedAt = null): void
    {
        $this->lockedAt = $objectLockedAt ?? new \DateTimeImmutable('now');
        $this->lockedBy = $objectLockedBy;
    }

    public function unlock(): void
    {
        $this->lockedAt = null;
        $this->lockedBy = null;
    }

    public function isLocked(): bool
    {
        return null !== $this->lockedAt;
    }
}
