<?php

declare(strict_types=1);

namespace App\Objecting\EntityInterface;

interface ObjectFingerprintedInterface
{
    public function getObjectHash(): ?string;

    public function setObjectHash(?string $objectHash): void;

    public function getObjectChecksum(): ?string;

    public function setObjectChecksum(?string $objectChecksum): void;

    public function getObjectAlgorithm(): ?string;

    public function setObjectAlgorithm(?string $objectAlgorithm): void;
}
