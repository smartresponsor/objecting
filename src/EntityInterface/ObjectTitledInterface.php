<?php

declare(strict_types=1);

namespace App\Objecting\EntityInterface;

interface ObjectTitledInterface
{
    public function getFirstTitle(): ?string;

    public function setFirstTitle(?string $firstTitle): void;

    public function getMiddleTitle(): ?string;

    public function setMiddleTitle(?string $middleTitle): void;

    public function getLastTitle(): ?string;

    public function setLastTitle(?string $lastTitle): void;
}
