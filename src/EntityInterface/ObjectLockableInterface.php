<?php

declare(strict_types=1);

namespace App\Objecting\EntityInterface;

interface ObjectLockableInterface
{
    public function isLocked(): bool;

    public function getLockedAt(): ?\DateTimeImmutable;

    public function getLockedBy(): ?string;

    public function lock(?string $lockedBy = null, ?\DateTimeImmutable $lockedAt = null): void;

    public function unlock(): void;
}
