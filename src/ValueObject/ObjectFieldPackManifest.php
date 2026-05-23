<?php

declare(strict_types=1);

namespace App\Objecting\ValueObject;

final readonly class ObjectFieldPackManifest
{
    /** @param list<string> $columns */
    public function __construct(
        private string $name,
        private string $embeddableClass,
        private string $traitClass,
        private string $interfaceClass,
        private array $columns,
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function embeddableClass(): string
    {
        return $this->embeddableClass;
    }

    public function traitClass(): string
    {
        return $this->traitClass;
    }

    public function interfaceClass(): string
    {
        return $this->interfaceClass;
    }

    /** @return list<string> */
    public function columns(): array
    {
        return $this->columns;
    }
}
