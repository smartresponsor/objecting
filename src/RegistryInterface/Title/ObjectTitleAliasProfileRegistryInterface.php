<?php

declare(strict_types=1);

namespace App\Objecting\RegistryInterface\Title;

use App\Objecting\ValueObject\ObjectTitleAliasProfile;

interface ObjectTitleAliasProfileRegistryInterface
{
    /** @return array<string, ObjectTitleAliasProfile> */
    public function all(): array;

    public function get(string $name): ObjectTitleAliasProfile;

    public function has(string $name): bool;
}
