<?php

declare(strict_types=1);

namespace App\Objecting\EntityInterface;

interface ObjectLockableInterface
{
    public function isObjectLocked(): bool;

    public function getObjectLockedAt(): ?\DateTimeImmutable;

    public function getObjectLockedBy(): ?string;

    public function lockObject(?string $objectLockedBy = null, ?\DateTimeImmutable $objectLockedAt = null): void;

    public function unlockObject(): void;
}
