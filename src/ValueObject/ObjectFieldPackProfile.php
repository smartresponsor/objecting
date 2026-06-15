<?php

declare(strict_types=1);

namespace App\Objecting\ValueObject;

final readonly class ObjectFieldPackProfile
{
    /** @param list<string> $fieldPacks */
    public function __construct(private string $name, private array $fieldPacks)
    {
        if ('' === $this->name) {
            throw new \InvalidArgumentException('Objecting field-pack profile name cannot be empty.');
        }
        foreach ($this->fieldPacks as $fieldPack) {
            if (!ObjectFieldPackName::isKnown($fieldPack)) {
                throw new \InvalidArgumentException(sprintf('Unknown Objecting field pack "%s" in profile "%s".', $fieldPack, $this->name));
            }
        }
        if (array_values(array_unique($this->fieldPacks)) !== $this->fieldPacks) {
            throw new \InvalidArgumentException(sprintf('Duplicate Objecting field pack in profile "%s".', $this->name));
        }
    }

    public function name(): string
    {
        return $this->name;
    }

    /** @return list<string> */
    public function fieldPacks(): array
    {
        return $this->fieldPacks;
    }

    public function contains(string $fieldPack): bool
    {
        return in_array($fieldPack, $this->fieldPacks, true);
    }
}
