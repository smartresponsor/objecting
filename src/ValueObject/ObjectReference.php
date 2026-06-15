<?php

declare(strict_types=1);

namespace App\Objecting\ValueObject;

/**
 * Canonical cross-component reference.
 *
 * This value object replaces ad-hoc strings such as vendorId/productId/orderId
 * when the referenced aggregate is owned by another component and must not be
 * linked through a Doctrine foreign key.
 */
final readonly class ObjectReference implements \Stringable
{
    public function __construct(
        private string $component,
        private string $aggregate,
        private string $identifier,
        private ?string $display = null,
        private ?string $version = null,
    ) {
        self::assertToken($component, 'component');
        self::assertToken($aggregate, 'aggregate');
        if ('' === trim($identifier)) {
            throw new \InvalidArgumentException('Object reference identifier must not be empty.');
        }
    }

    public static function fromString(string $reference): self
    {
        $parts = explode(':', $reference, 5);
        if (count($parts) < 3) {
            throw new \InvalidArgumentException('Object reference must use component:aggregate:identifier format.');
        }

        return new self($parts[0], $parts[1], $parts[2], $parts[3] ?? null, $parts[4] ?? null);
    }

    public static function vendor(string $identifier, ?string $display = null): self
    {
        return new self('vendoring', 'vendor', $identifier, $display);
    }

    public static function product(string $identifier, ?string $display = null): self
    {
        return new self('cataloging', 'product', $identifier, $display);
    }

    public static function order(string $identifier, ?string $display = null): self
    {
        return new self('ordering', 'order', $identifier, $display);
    }

    public static function payment(string $identifier, ?string $display = null): self
    {
        return new self('paying', 'payment', $identifier, $display);
    }

    public static function shipment(string $identifier, ?string $display = null): self
    {
        return new self('shipping', 'shipment', $identifier, $display);
    }

    public function component(): string
    {
        return $this->component;
    }

    public function aggregate(): string
    {
        return $this->aggregate;
    }

    public function identifier(): string
    {
        return $this->identifier;
    }

    public function display(): ?string
    {
        return $this->display;
    }

    public function version(): ?string
    {
        return $this->version;
    }

    public function withoutDisplay(): self
    {
        return new self($this->component, $this->aggregate, $this->identifier, null, $this->version);
    }

    public function __toString(): string
    {
        $value = $this->component.':'.$this->aggregate.':'.$this->identifier;
        if (null !== $this->display) {
            $value .= ':'.$this->display;
        }
        if (null !== $this->version) {
            $value .= ':'.$this->version;
        }

        return $value;
    }

    /** @return array{component:string, aggregate:string, identifier:string, display:?string, version:?string} */
    public function toArray(): array
    {
        return [
            'component' => $this->component,
            'aggregate' => $this->aggregate,
            'identifier' => $this->identifier,
            'display' => $this->display,
            'version' => $this->version,
        ];
    }

    private static function assertToken(string $value, string $name): void
    {
        if (1 !== preg_match('/^[a-z][a-z0-9_\\-]*$/', $value)) {
            throw new \InvalidArgumentException(sprintf('Object reference %s must be a canonical lowercase token.', $name));
        }
    }
}
