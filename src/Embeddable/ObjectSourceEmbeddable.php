<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectSourceEmbeddable
{
    #[ORM\Column(name: 'object_source', type: 'string', length: 190, nullable: true)]
    private ?string $objectSource = null;

    #[ORM\Column(name: 'object_provider', type: 'string', length: 190, nullable: true)]
    private ?string $objectProvider = null;

    #[ORM\Column(name: 'object_external_id', type: 'string', length: 190, nullable: true)]
    private ?string $objectExternalId = null;

    #[ORM\Column(name: 'object_source_type', type: 'string', length: 120, nullable: true)]
    private ?string $objectSourceType = null;

    public function __construct(?string $objectSource = null, ?string $objectProvider = null, ?string $objectExternalId = null, ?string $objectSourceType = null)
    {
        $this->objectSource = $objectSource;
        $this->objectProvider = $objectProvider;
        $this->objectExternalId = $objectExternalId;
        $this->objectSourceType = $objectSourceType;
    }

    public function getObjectSource(): ?string
    {
        return $this->objectSource;
    }

    public function setObjectSource(?string $objectSource): void
    {
        $this->objectSource = $objectSource;
    }

    public function getObjectProvider(): ?string
    {
        return $this->objectProvider;
    }

    public function setObjectProvider(?string $objectProvider): void
    {
        $this->objectProvider = $objectProvider;
    }

    public function getObjectExternalId(): ?string
    {
        return $this->objectExternalId;
    }

    public function setObjectExternalId(?string $objectExternalId): void
    {
        $this->objectExternalId = $objectExternalId;
    }

    public function getObjectSourceType(): ?string
    {
        return $this->objectSourceType;
    }

    public function setObjectSourceType(?string $objectSourceType): void
    {
        $this->objectSourceType = $objectSourceType;
    }
}
