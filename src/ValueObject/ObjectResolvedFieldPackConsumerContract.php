<?php

declare(strict_types=1);

namespace App\Objecting\ValueObject;

final readonly class ObjectResolvedFieldPackConsumerContract
{
    /**
     * @param list<string> $explicitFieldPacks
     * @param list<string> $effectiveFieldPacks
     */
    public function __construct(
        private string $component,
        private string $businessStem,
        private string $entityClass,
        private array $explicitFieldPacks,
        private array $effectiveFieldPacks,
        private ?string $fieldPackProfile = null,
        private ?ObjectTitleAliasMap $titleAliasMap = null,
        private ?string $titleAliasProfile = null,
    ) {
        foreach (['component' => $this->component, 'business stem' => $this->businessStem, 'entity class' => $this->entityClass] as $label => $value) {
            if ('' === $value) {
                throw new \InvalidArgumentException(sprintf('Resolved Objecting consumer %s cannot be empty.', $label));
            }
        }

        if ([] === $this->effectiveFieldPacks) {
            throw new \InvalidArgumentException(sprintf('Resolved Objecting consumer contract for "%s" has no effective field packs.', $this->component));
        }

        foreach ([$this->explicitFieldPacks, $this->effectiveFieldPacks] as $fieldPackList) {
            foreach ($fieldPackList as $fieldPack) {
                if (!ObjectFieldPackName::isKnown($fieldPack)) {
                    throw new \InvalidArgumentException(sprintf('Unknown Objecting field pack "%s" in resolved consumer contract for "%s".', $fieldPack, $this->component));
                }
            }
        }

        if (array_values(array_unique($this->effectiveFieldPacks)) !== $this->effectiveFieldPacks) {
            throw new \InvalidArgumentException(sprintf('Duplicate effective Objecting field pack in resolved consumer contract for "%s".', $this->component));
        }

        if ((null !== $this->titleAliasMap || null !== $this->titleAliasProfile) && !$this->usesFieldPack(ObjectFieldPackName::TITLE)) {
            throw new \InvalidArgumentException(sprintf('Resolved Objecting consumer contract for "%s" declares title aliases without the object_title field pack.', $this->component));
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
    public function explicitFieldPacks(): array
    {
        return $this->explicitFieldPacks;
    }

    /** @return list<string> */
    public function effectiveFieldPacks(): array
    {
        return $this->effectiveFieldPacks;
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
        return in_array($fieldPack, $this->effectiveFieldPacks, true);
    }
}
