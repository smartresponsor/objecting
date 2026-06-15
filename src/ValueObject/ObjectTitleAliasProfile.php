<?php

declare(strict_types=1);

namespace App\Objecting\ValueObject;

final readonly class ObjectTitleAliasProfile
{
    public function __construct(private string $name, private ObjectTitleAliasMap $aliasMap)
    {
        if ('' === $this->name) {
            throw new \InvalidArgumentException('Objecting title-alias profile name cannot be empty.');
        }
    }

    public function name(): string
    {
        return $this->name;
    }

    public function aliasMap(): ObjectTitleAliasMap
    {
        return $this->aliasMap;
    }

    public function aliasFor(string $canonicalField): string
    {
        return $this->aliasMap->aliasFor($canonicalField);
    }

    /** @return array<string, string> */
    public function aliases(): array
    {
        return $this->aliasMap->all();
    }
}
