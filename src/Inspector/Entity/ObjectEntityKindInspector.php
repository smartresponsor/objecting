<?php

declare(strict_types=1);

namespace App\Objecting\Inspector\Entity;

use App\Objecting\InspectorInterface\Entity\ObjectEntityKindInspectorInterface;
use App\Objecting\RegistryInterface\Entity\ObjectEntityKindRegistryInterface;
use App\Objecting\RegistryInterface\FieldPack\ObjectFieldPackRegistryInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

final readonly class ObjectEntityKindInspector implements ObjectEntityKindInspectorInterface
{
    public function __construct(
        private ObjectEntityKindRegistryInterface $entityKindRegistry,
        private ObjectFieldPackRegistryInterface $fieldPackRegistry,
    ) {
    }

    /** @param ClassMetadata<object>|null $metadata */
    public function inspect(string $entityClass, ?ClassMetadata $metadata = null): array
    {
        if (!class_exists($entityClass)) {
            return [sprintf('Entity class "%s" does not exist.', $entityClass)];
        }

        $errors = [];
        $kinds = $this->entityKindRegistry->kindsForClass($entityClass);

        if ([] === $kinds) {
            return [sprintf('Entity class "%s" does not declare an Objecting entity kind.', $entityClass)];
        }

        if (1 !== count($kinds)) {
            $errors[] = sprintf('Entity class "%s" declares multiple mutually exclusive Objecting entity kinds: %s.', $entityClass, implode(', ', $kinds));
        }

        $effectiveTraits = $this->effectiveTraits($entityClass);
        $columnNames = null === $metadata ? null : $metadata->getColumnNames();

        foreach ($kinds as $kind) {
            foreach ($this->entityKindRegistry->requiredFieldPacks($kind) as $fieldPackName) {
                if (!$this->fieldPackRegistry->has($fieldPackName)) {
                    $errors[] = sprintf('Entity kind "%s" references unknown field pack "%s".', $kind, $fieldPackName);
                    continue;
                }

                $pack = $this->fieldPackRegistry->get($fieldPackName);
                if (!is_a($entityClass, $pack->interfaceClass(), true)) {
                    $errors[] = sprintf('Entity class "%s" must implement %s for required field pack %s.', $entityClass, $pack->interfaceClass(), $fieldPackName);
                }
                if (!in_array($pack->traitClass(), $effectiveTraits, true)) {
                    $errors[] = sprintf('Entity class "%s" must effectively compose %s for required field pack %s.', $entityClass, $pack->traitClass(), $fieldPackName);
                }

                if (null !== $columnNames) {
                    foreach ($pack->columns() as $column) {
                        if (!in_array($column, $columnNames, true)) {
                            $errors[] = sprintf('Doctrine metadata for "%s" is missing required column %s from field pack %s.', $entityClass, $column, $fieldPackName);
                        }
                    }
                }
            }
        }

        foreach ($this->entityKindRegistry->capabilities() as $capabilityName => $definition) {
            if (!is_a($entityClass, $definition['interface'], true)) {
                continue;
            }

            foreach ($definition['requires'] as $requiredCapability) {
                $requiredDefinition = $this->entityKindRegistry->capabilities()[$requiredCapability] ?? null;
                if (null === $requiredDefinition) {
                    $errors[] = sprintf('Capability "%s" requires unknown capability "%s".', $capabilityName, $requiredCapability);
                    continue;
                }
                if (!is_a($entityClass, $requiredDefinition['interface'], true)) {
                    $errors[] = sprintf('Entity class "%s" capability %s requires %s.', $entityClass, $capabilityName, $requiredDefinition['interface']);
                }
            }
        }

        return array_values(array_unique($errors));
    }

    /** @param class-string $className
     * @return list<class-string>
     */
    private function effectiveTraits(string $className): array
    {
        $traits = [];
        for ($class = $className; false !== $class; $class = get_parent_class($class)) {
            foreach (class_uses($class) ?: [] as $trait) {
                $traits[$trait] = $trait;
                foreach ($this->nestedTraits($trait) as $nestedTrait) {
                    $traits[$nestedTrait] = $nestedTrait;
                }
            }
        }

        return array_values($traits);
    }

    /** @return list<class-string> */
    private function nestedTraits(string $traitName): array
    {
        $traits = [];
        foreach (class_uses($traitName) ?: [] as $trait) {
            $traits[$trait] = $trait;
            foreach ($this->nestedTraits($trait) as $nestedTrait) {
                $traits[$nestedTrait] = $nestedTrait;
            }
        }

        return array_values($traits);
    }
}
