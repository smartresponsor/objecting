<?php

declare(strict_types=1);

namespace App\Objecting\EntityInterface;

interface ObjectSoftDeletableInterface
{
    public function isDeleted(): bool;

    public function getDeletedAt(): ?\DateTimeImmutable;

    public function getDeletedBy(): ?string;

    public function delete(?string $deletedBy = null, ?\DateTimeImmutable $deletedAt = null): void;

    public function restore(): void;
}
