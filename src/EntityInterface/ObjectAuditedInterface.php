<?php

declare(strict_types=1);

namespace App\Objecting\EntityInterface;

interface ObjectAuditedInterface
{
    public function getObjectCreatedAt(): \DateTimeImmutable;

    public function getObjectModifiedAt(): ?\DateTimeImmutable;

    public function getObjectCreatedBy(): ?string;

    public function getObjectModifiedBy(): ?string;

    public function touchModified(?\DateTimeImmutable $modifiedAt = null, ?string $modifiedBy = null): void;
}
