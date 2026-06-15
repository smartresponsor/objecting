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

    public function isObjectLocked(): bool
    {
        return $this->objectLockEmbeddable()->isObjectLocked();
    }

    public function getObjectLockedAt(): ?\DateTimeImmutable
    {
        return $this->objectLockEmbeddable()->getObjectLockedAt();
    }

    public function getObjectLockedBy(): ?string
    {
        return $this->objectLockEmbeddable()->getObjectLockedBy();
    }

    public function getLockedAt(): ?\DateTimeImmutable
    {
        return $this->objectLockEmbeddable()->getLockedAt();
    }

    public function getLockedBy(): ?string
    {
        return $this->objectLockEmbeddable()->getLockedBy();
    }

    public function lockObject(?string $objectLockedBy = null, ?\DateTimeImmutable $objectLockedAt = null): void
    {
        $this->objectLockEmbeddable()->lock($objectLockedBy, $objectLockedAt);
    }

    public function lock(?string $objectLockedBy = null, ?\DateTimeImmutable $objectLockedAt = null): void
    {
        $this->lockObject($objectLockedBy, $objectLockedAt);
    }

    public function unlockObject(): void
    {
        $this->objectLockEmbeddable()->unlock();
    }

    public function unlock(): void
    {
        $this->unlockObject();
    }
}
