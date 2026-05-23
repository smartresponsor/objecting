<?php

declare(strict_types=1);

namespace App\Objecting\Service\FieldPack;

use App\Objecting\ServiceInterface\FieldPack\ObjectFieldPackConsumerContractResolverInterface;
use App\Objecting\ServiceInterface\FieldPack\ObjectFieldPackProfileRegistryInterface;
use App\Objecting\ValueObject\ObjectFieldPackConsumerContract;
use App\Objecting\ValueObject\ObjectFieldPackName;
use App\Objecting\ValueObject\ObjectResolvedFieldPackConsumerContract;

final readonly class ObjectFieldPackConsumerContractResolver implements ObjectFieldPackConsumerContractResolverInterface
{
    public function __construct(
        private ?ObjectFieldPackProfileRegistryInterface $profileRegistry = null,
    ) {
    }

    public function resolve(ObjectFieldPackConsumerContract $contract): ObjectResolvedFieldPackConsumerContract
    {
        $effectiveFieldPacks = $contract->fieldPacks();

        if (null !== $contract->fieldPackProfile()) {
            $profileRegistry = $this->profileRegistry ?? new ObjectFieldPackProfileRegistry();
            $effectiveFieldPacks = $this->mergeFieldPacks($effectiveFieldPacks, $profileRegistry->get($contract->fieldPackProfile())->fieldPacks());
        }

        if ([] === $effectiveFieldPacks) {
            throw new \InvalidArgumentException(sprintf('Objecting consumer contract for "%s" must declare field packs directly or through a field-pack profile.', $contract->component()));
        }

        if ((null !== $contract->titleAliasMap() || null !== $contract->titleAliasProfile()) && !in_array(ObjectFieldPackName::TITLE, $effectiveFieldPacks, true)) {
            throw new \InvalidArgumentException(sprintf('Objecting consumer contract for "%s" declares title aliases without the object_title field pack.', $contract->component()));
        }

        return new ObjectResolvedFieldPackConsumerContract(
            component: $contract->component(),
            businessStem: $contract->businessStem(),
            entityClass: $contract->entityClass(),
            explicitFieldPacks: $contract->fieldPacks(),
            effectiveFieldPacks: $effectiveFieldPacks,
            fieldPackProfile: $contract->fieldPackProfile(),
            titleAliasMap: $contract->titleAliasMap(),
            titleAliasProfile: $contract->titleAliasProfile(),
        );
    }

    /**
     * @param list<string> $first
     * @param list<string> $second
     *
     * @return list<string>
     */
    private function mergeFieldPacks(array $first, array $second): array
    {
        $merged = [];

        foreach (array_merge($first, $second) as $fieldPack) {
            if (!in_array($fieldPack, $merged, true)) {
                $merged[] = $fieldPack;
            }
        }

        return $merged;
    }
}
