<?php

declare(strict_types=1);

namespace App\Objecting\EntityInterface;

interface ObjectAuditedInterface
{
    public function getCreatedAt(): \DateTimeImmutable;

    public function getModifiedAt(): ?\DateTimeImmutable;

    public function getCreatedBy(): ?string;

    public function getModifiedBy(): ?string;

    public function touchModified(?\DateTimeImmutable $modifiedAt = null, ?string $modifiedBy = null): void;
}
