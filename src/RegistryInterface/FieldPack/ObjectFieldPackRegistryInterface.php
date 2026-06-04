<?php

declare(strict_types=1);

namespace App\Objecting\RegistryInterface\FieldPack;

use App\Objecting\Manifest\ObjectFieldPackManifest;

interface ObjectFieldPackRegistryInterface
{
    /** @return array<string, ObjectFieldPackManifest> */
    public function all(): array;

    public function get(string $name): ObjectFieldPackManifest;

    public function has(string $name): bool;
}
