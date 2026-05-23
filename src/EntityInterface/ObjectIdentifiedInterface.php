<?php

declare(strict_types=1);

namespace App\Objecting\EntityInterface;

interface ObjectIdentifiedInterface
{
    public function getObjectUuid(): string;

    public function getObjectSlug(): ?string;

    public function setObjectSlug(?string $objectSlug): void;
}
