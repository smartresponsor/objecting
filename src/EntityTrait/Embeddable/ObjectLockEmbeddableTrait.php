<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use App\Objecting\Embeddable\ObjectLockEmbeddable;
use Doctrine\ORM\Mapping as ORM;

trait ObjectLockEmbeddableTrait
{
    #[ORM\Embedded(class: ObjectLockEmbeddable::class, columnPrefix: false)]
    private ObjectLockEmbeddable $objectLock;

    protected function initializeObjectLock(): void
    {
        $this->objectLock = new ObjectLockEmbeddable();
    }

    private function objectLockEmbeddable(): ObjectLockEmbeddable
    {
        if (!isset($this->objectLock)) {
            $this->objectLock = new ObjectLockEmbeddable();
        }

        return $this->objectLock;
    }

    public function isLocked(): bool
    {
        return $this->objectLockEmbeddable()->isLocked();
    }

    public function getLockedAt(): ?\DateTimeImmutable
    {
        return $this->objectLockEmbeddable()->getLockedAt();
    }

    public function getLockedBy(): ?string
    {
        return $this->objectLockEmbeddable()->getLockedBy();
    }

    public function lock(?string $lockedBy = null, ?\DateTimeImmutable $lockedAt = null): void
    {
        $this->objectLockEmbeddable()->lock($lockedBy, $lockedAt);
    }

    public function unlock(): void
    {
        $this->objectLockEmbeddable()->unlock();
    }
}
