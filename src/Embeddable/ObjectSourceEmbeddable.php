<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectSourceEmbeddable
{
    #[ORM\Column(name: 'source', type: 'string', length: 190, nullable: true)]
    private ?string $source = null;

    #[ORM\Column(name: 'provider', type: 'string', length: 190, nullable: true)]
    private ?string $provider = null;

    #[ORM\Column(name: 'external_id', type: 'string', length: 190, nullable: true)]
    private ?string $externalId = null;

    #[ORM\Column(name: 'source_type', type: 'string', length: 120, nullable: true)]
    private ?string $sourceType = null;

    public function __construct(?string $objectSource = null, ?string $objectProvider = null, ?string $objectExternalId = null, ?string $objectSourceType = null)
    {
        $this->source = $objectSource;
        $this->provider = $objectProvider;
        $this->externalId = $objectExternalId;
        $this->sourceType = $objectSourceType;
    }

    public function getObjectSource(): ?string
    {
        return $this->source;
    }

    public function setObjectSource(?string $objectSource): void
    {
        $this->source = $objectSource;
    }

    public function getObjectProvider(): ?string
    {
        return $this->provider;
    }

    public function setObjectProvider(?string $objectProvider): void
    {
        $this->provider = $objectProvider;
    }

    public function getObjectExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setObjectExternalId(?string $objectExternalId): void
    {
        $this->externalId = $objectExternalId;
    }

    public function getObjectSourceType(): ?string
    {
        return $this->sourceType;
    }

    public function setObjectSourceType(?string $objectSourceType): void
    {
        $this->sourceType = $objectSourceType;
    }
}
