<?php

declare(strict_types=1);

namespace App\Objecting\EntityInterface;

interface ObjectSoftDeletableInterface
{
    public function isObjectDeleted(): bool;

    public function getObjectDeletedAt(): ?\DateTimeImmutable;

    public function getObjectDeletedBy(): ?string;

    public function deleteObject(?string $deletedBy = null, ?\DateTimeImmutable $deletedAt = null): void;

    public function restoreObject(): void;
}
