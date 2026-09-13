<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectFingerprintEmbeddable
{
    #[ORM\Column(name: 'object_hash', type: 'string', length: 190, nullable: true)]
    private ?string $objectHash = null;

    #[ORM\Column(name: 'object_checksum', type: 'string', length: 190, nullable: true)]
    private ?string $objectChecksum = null;

    #[ORM\Column(name: 'object_algorithm', type: 'string', length: 64, nullable: true)]
    private ?string $objectAlgorithm = null;

    public function __construct(?string $objectHash = null, ?string $objectChecksum = null, ?string $objectAlgorithm = null)
    {
        $this->objectHash = $objectHash;
        $this->objectChecksum = $objectChecksum;
        $this->objectAlgorithm = $objectAlgorithm;
    }

    public function getObjectHash(): ?string
    {
        return $this->objectHash;
    }

    public function setObjectHash(?string $objectHash): void
    {
        $this->objectHash = $objectHash;
    }

    public function getObjectChecksum(): ?string
    {
        return $this->objectChecksum;
    }

    public function setObjectChecksum(?string $objectChecksum): void
    {
        $this->objectChecksum = $objectChecksum;
    }

    public function getObjectAlgorithm(): ?string
    {
        return $this->objectAlgorithm;
    }

    public function setObjectAlgorithm(?string $objectAlgorithm): void
    {
        $this->objectAlgorithm = $objectAlgorithm;
    }
}
