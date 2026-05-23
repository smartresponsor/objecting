<?php

declare(strict_types=1);

namespace App\Objecting\EntityInterface;

interface ObjectStatefulInterface
{
    public function isObjectActive(): bool;

    public function setObjectActive(bool $objectActive): void;

    public function isObjectEnabled(): bool;

    public function setObjectEnabled(bool $objectEnabled): void;

    public function getObjectStatus(): ?string;

    public function setObjectStatus(?string $objectStatus): void;
}
