<?php

declare(strict_types=1);

namespace App\Objecting\EntityInterface;

interface ObjectAuditedInterface
{
    public function getObjectCreatedAt(): \DateTimeImmutable;

    public function getObjectUpdatedAt(): ?\DateTimeImmutable;

    public function getObjectCreatedBy(): ?string;

    public function getObjectUpdatedBy(): ?string;

    public function touchObject(?\DateTimeImmutable $updatedAt = null, ?string $updatedBy = null): void;
}
