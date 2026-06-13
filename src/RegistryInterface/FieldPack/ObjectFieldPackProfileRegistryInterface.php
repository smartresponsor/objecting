<?php

declare(strict_types=1);

namespace App\Objecting\RegistryInterface\FieldPack;

use App\Objecting\ValueObject\ObjectFieldPackProfile;

interface ObjectFieldPackProfileRegistryInterface
{
    /** @return array<string, ObjectFieldPackProfile> */
    public function all(): array;

    public function get(string $nameEntity): ObjectFieldPackProfile;

    public function has(string $nameEntity): bool;
}
