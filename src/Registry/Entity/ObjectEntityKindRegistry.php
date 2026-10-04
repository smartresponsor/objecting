<?php

declare(strict_types=1);

namespace App\Objecting\Registry\Entity;

use App\Objecting\RegistryInterface\Entity\ObjectEntityKindRegistryInterface;
use Symfony\Component\Yaml\Yaml;

final class ObjectEntityKindRegistry implements ObjectEntityKindRegistryInterface
{
    /** @var array<string, mixed>|null */
    private ?array $manifest = null;

    public function __construct(private readonly string $manifestPath)
    {
    }

    public function entityKinds(): array
    {
        $kinds = $this->manifest()['entity_kinds'] ?? null;
        if (!\is_array($kinds)) {
            throw new \RuntimeException('Objecting entity-kind manifest is missing entity_kinds.');
        }

        $result = [];
        foreach ($kinds as $name => $definition) {
            if (!\is_string($name) || !\is_array($definition)) {
                throw new \RuntimeException('Objecting entity-kind manifest contains an invalid entity kind.');
            }

            $interface = $definition['interface'] ?? null;
            $required = $definition['required_field_packs'] ?? [];
            if (!\is_string($interface) || !interface_exists($interface) || !\is_array($required)) {
                throw new \RuntimeException(sprintf('Invalid Objecting entity-kind definition "%s".', $name));
            }

            $result[$name] = [
                'interface' => $interface,
                'required_field_packs' => array_values(array_filter($required, 'is_string')),
            ];
        }

        return $result;
    }

    public function capabilities(): array
    {
        $capabilities = $this->manifest()['capabilities'] ?? [];
        if (!\is_array($capabilities)) {
            throw new \RuntimeException('Objecting entity-kind manifest contains invalid capabilities.');
        }

        $result = [];
        foreach ($capabilities as $name => $definition) {
            if (!\is_string($name) || !\is_array($definition) || !\is_string($definition['interface'] ?? null) || !interface_exists($definition['interface'])) {
                throw new \RuntimeException('Objecting entity-kind manifest contains an invalid capability.');
            }
            /** @var class-string $interface */
            $interface = $definition['interface'];
            $result[$name] = [
                'interface' => $interface,
                'requires' => array_values(array_filter($definition['requires'] ?? [], 'is_string')),
            ];
        }

        return $result;
    }

    public function kindsForClass(string $className): array
    {
        $kinds = [];
        foreach ($this->entityKinds() as $name => $definition) {
            if (is_a($className, $definition['interface'], true)) {
                $kinds[] = $name;
            }
        }

        return $kinds;
    }

    public function requiredFieldPacks(string $kind): array
    {
        return $this->entityKinds()[$kind]['required_field_packs'] ?? throw new \InvalidArgumentException(sprintf('Unknown Objecting entity kind "%s".', $kind));
    }

    /** @return array<string, mixed> */
    private function manifest(): array
    {
        $manifest = $this->manifest ??= Yaml::parseFile($this->manifestPath);
        if (!\is_array($manifest)) {
            throw new \RuntimeException('Objecting entity-kind manifest must contain a YAML mapping.');
        }

        return $manifest;
    }
}
