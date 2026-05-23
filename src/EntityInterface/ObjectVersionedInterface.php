<?php

declare(strict_types=1);

namespace App\Objecting\EntityInterface;

interface ObjectVersionedInterface
{
    public function getObjectVersion(): int;

    public function getObjectEtag(): ?string;

    public function bumpObjectVersion(?string $objectEtag = null): void;
}
