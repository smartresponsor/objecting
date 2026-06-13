<?php

declare(strict_types=1);

namespace App\Objecting\ValueObject;

final readonly class ObjectFieldPackProfile
{
    /** @param list<string> $fieldPacks */
    public function __construct(private string $nameEntity, private array $fieldPacks)
    {
        if ('' === $this->nameEntity) {
            throw new \InvalidArgumentException('Objecting field-pack profile nameEntity cannot be empty.');
        }
        foreach ($this->fieldPacks as $fieldPack) {
            if (!ObjectFieldPackName::isKnown($fieldPack)) {
                throw new \InvalidArgumentException(sprintf('Unknown Objecting field pack "%s" in profile "%s".', $fieldPack, $this->nameEntity));
            }
        }
        if (array_values(array_unique($this->fieldPacks)) !== $this->fieldPacks) {
            throw new \InvalidArgumentException(sprintf('Duplicate Objecting field pack in profile "%s".', $this->nameEntity));
        }
    }

    public function nameEntity(): string
    {
        return $this->nameEntity;
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
