<?php

declare(strict_types=1);

namespace App\Objecting\EntityInterface;

interface ObjectConfigurableInterface
{
    /** @return array<string, mixed> */
    public function getObjectConfig(): array;

    /** @param array<string, mixed> $objectConfig */
    public function setObjectConfig(array $objectConfig): void;
}
