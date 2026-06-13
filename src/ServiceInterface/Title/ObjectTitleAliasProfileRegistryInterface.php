<?php

declare(strict_types=1);

namespace App\Objecting\ServiceInterface\Title;

use App\Objecting\ValueObject\ObjectTitleAliasProfile;

interface ObjectTitleAliasProfileRegistryInterface
{
    /** @return array<string, ObjectTitleAliasProfile> */
    public function all(): array;

    public function get(string $nameEntity): ObjectTitleAliasProfile;

    public function has(string $nameEntity): bool;
}
