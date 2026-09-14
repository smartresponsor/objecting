<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use App\Objecting\ValueObject\ObjectReference;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectReferenceEmbeddable
{
    #[ORM\Column(name: 'reference_component', type: 'string', length: 64, nullable: true)]
    private ?string $referenceComponent = null;

    #[ORM\Column(name: 'reference_aggregate', type: 'string', length: 64, nullable: true)]
    private ?string $referenceAggregate = null;

    #[ORM\Column(name: 'reference_identifier', type: 'string', length: 128, nullable: true)]
    private ?string $referenceIdentifier = null;

    #[ORM\Column(name: 'reference_display', type: 'string', length: 255, nullable: true)]
    private ?string $referenceDisplay = null;

    #[ORM\Column(name: 'reference_version', type: 'string', length: 64, nullable: true)]
    private ?string $referenceVersion = null;

    public static function fromReference(?ObjectReference $reference): self
    {
        $self = new self();
        $self->setReference($reference);

        return $self;
    }

    public function hasReference(): bool
    {
        return null !== $this->referenceComponent
            && null !== $this->referenceAggregate
            && null !== $this->referenceIdentifier;
    }

    public function reference(): ?ObjectReference
    {
        $component = $this->referenceComponent;
        $aggregate = $this->referenceAggregate;
        $identifier = $this->referenceIdentifier;

        if (null === $component || null === $aggregate || null === $identifier) {
            return null;
        }

        return new ObjectReference(
            $component,
            $aggregate,
            $identifier,
            $this->referenceDisplay,
            $this->referenceVersion,
        );
    }

    public function setReference(?ObjectReference $reference): void
    {
        $this->referenceComponent = $reference?->component();
        $this->referenceAggregate = $reference?->aggregate();
        $this->referenceIdentifier = $reference?->identifier();
        $this->referenceDisplay = $reference?->display();
        $this->referenceVersion = $reference?->version();
    }
}
