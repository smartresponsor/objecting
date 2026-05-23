<?php

declare(strict_types=1);

namespace App\Objecting\ServiceInterface\FieldPack;

use App\Objecting\ValueObject\ObjectFieldPackProfile;

interface ObjectFieldPackProfileRegistryInterface
{
    /** @return array<string, ObjectFieldPackProfile> */
    public function all(): array;

    public function get(string $name): ObjectFieldPackProfile;

    public function has(string $name): bool;
}
