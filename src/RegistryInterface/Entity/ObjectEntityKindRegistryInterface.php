<?php

declare(strict_types=1);

namespace App\Objecting\RegistryInterface\Entity;

interface ObjectEntityKindRegistryInterface
{
    /** @return array<string, array{interface: class-string, required_field_packs: list<string>}> */
    public function entityKinds(): array;

    /** @return array<string, array{interface: class-string, requires: list<string>}> */
    public function capabilities(): array;

    /** @return list<string> */
    public function kindsForClass(string $className): array;

    /** @return list<string> */
    public function requiredFieldPacks(string $kind): array;
}
