<?php

declare(strict_types=1);

namespace App\Objecting\Contract;

use App\Objecting\ValueObject\ObjectFieldPackName;
use App\Objecting\ValueObject\ObjectFieldPackProfileName;
use App\Objecting\ValueObject\ObjectTitleAliasMap;
use App\Objecting\ValueObject\ObjectTitleAliasProfileName;

final readonly class ObjectFieldPackConsumerContract
{
    /** @param list<string> $fieldPacks */
    public function __construct(
        private string $component,
        private string $businessStem,
        private string $entityClass,
        private array $fieldPacks,
        private ?string $fieldPackProfile = null,
        private ?ObjectTitleAliasMap $titleAliasMap = null,
        private ?string $titleAliasProfile = null,
    ) {
        foreach (['component' => $this->component, 'business stem' => $this->businessStem, 'entity class' => $this->entityClass] as $label => $value) {
            if ('' === $value) {
                throw new \InvalidArgumentException(sprintf('Objecting consumer %s cannot be empty.', $label));
            }
        }

        foreach ($this->fieldPacks as $fieldPack) {
            if (!ObjectFieldPackName::isKnown($fieldPack)) {
                throw new \InvalidArgumentException(sprintf('Unknown Objecting field pack "%s" in consumer contract for "%s".', $fieldPack, $this->component));
            }
        }

        if (array_values(array_unique($this->fieldPacks)) !== $this->fieldPacks) {
            throw new \InvalidArgumentException(sprintf('Duplicate Objecting field pack in consumer contract for "%s".', $this->component));
        }

        if (null !== $this->fieldPackProfile && !ObjectFieldPackProfileName::isKnown($this->fieldPackProfile)) {
            throw new \InvalidArgumentException(sprintf('Unknown Objecting field-pack profile "%s" in consumer contract for "%s".', $this->fieldPackProfile, $this->component));
        }

        if (null !== $this->titleAliasProfile && !ObjectTitleAliasProfileName::isKnown($this->titleAliasProfile)) {
            throw new \InvalidArgumentException(sprintf('Unknown Objecting title-alias profile "%s" in consumer contract for "%s".', $this->titleAliasProfile, $this->component));
        }

        if (null !== $this->titleAliasMap && null !== $this->titleAliasProfile) {
            throw new \InvalidArgumentException(sprintf('Objecting consumer contract for "%s" cannot declare both an inline title alias map and a title-alias profile.', $this->component));
        }
    }

    public function component(): string
    {
        return $this->component;
    }

    public function businessStem(): string
    {
        return $this->businessStem;
    }

    public function entityClass(): string
    {
        return $this->entityClass;
    }

    /** @return list<string> */
    public function fieldPacks(): array
    {
        return $this->fieldPacks;
    }

    public function fieldPackProfile(): ?string
    {
        return $this->fieldPackProfile;
    }

    public function titleAliasMap(): ?ObjectTitleAliasMap
    {
        return $this->titleAliasMap;
    }

    public function titleAliasProfile(): ?string
    {
        return $this->titleAliasProfile;
    }

    public function usesFieldPack(string $fieldPack): bool
    {
        return in_array($fieldPack, $this->fieldPacks, true);
    }

    public function declaresFieldPackProfile(): bool
    {
        return null !== $this->fieldPackProfile;
    }

    public function declaresTitleAliases(): bool
    {
        return null !== $this->titleAliasMap || null !== $this->titleAliasProfile;
    }
}
