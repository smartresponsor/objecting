<?php

declare(strict_types=1);

namespace App\Objecting\EntityInterface;

interface ObjectCodedInterface
{
    public function getObjectCode(): ?string;

    public function setObjectCode(?string $objectCode): void;
}
