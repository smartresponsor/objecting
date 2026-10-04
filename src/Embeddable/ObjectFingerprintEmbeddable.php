<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectFingerprintEmbeddable
{
    #[ORM\Column(name: 'hash', type: 'string', length: 190, nullable: true)]
    private ?string $hash = null;

    #[ORM\Column(name: 'checksum', type: 'string', length: 190, nullable: true)]
    private ?string $checksum = null;

    #[ORM\Column(name: 'algorithm', type: 'string', length: 64, nullable: true)]
    private ?string $algorithm = null;

    public function __construct(?string $objectHash = null, ?string $objectChecksum = null, ?string $objectAlgorithm = null)
    {
        $this->hash = $objectHash;
        $this->checksum = $objectChecksum;
        $this->algorithm = $objectAlgorithm;
    }

    public function getObjectHash(): ?string
    {
        return $this->hash;
    }

    public function setObjectHash(?string $objectHash): void
    {
        $this->hash = $objectHash;
    }

    public function getObjectChecksum(): ?string
    {
        return $this->checksum;
    }

    public function setObjectChecksum(?string $objectChecksum): void
    {
        $this->checksum = $objectChecksum;
    }

    public function getObjectAlgorithm(): ?string
    {
        return $this->algorithm;
    }

    public function setObjectAlgorithm(?string $objectAlgorithm): void
    {
        $this->algorithm = $objectAlgorithm;
    }
}
