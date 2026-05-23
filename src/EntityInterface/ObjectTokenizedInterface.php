<?php

declare(strict_types=1);

namespace App\Objecting\EntityInterface;

interface ObjectTokenizedInterface
{
    public function getObjectToken(): ?string;

    public function setObjectToken(?string $objectToken): void;

    public function isObjectTokenValid(?\DateTimeImmutable $at = null): bool;
}
