<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use App\Objecting\Embeddable\ObjectFingerprintEmbeddable;
use Doctrine\ORM\Mapping as ORM;

trait ObjectFingerprintEmbeddableTrait
{
    #[ORM\Embedded(class: ObjectFingerprintEmbeddable::class, columnPrefix: false)]
    private ObjectFingerprintEmbeddable $objectFingerprint;

    protected function initializeObjectFingerprint(?string $objectHash = null, ?string $objectChecksum = null, ?string $objectAlgorithm = null): void
    {
        $this->objectFingerprint = new ObjectFingerprintEmbeddable($objectHash, $objectChecksum, $objectAlgorithm);
    }

    private function objectFingerprintEmbeddable(): ObjectFingerprintEmbeddable
    {
        if (!isset($this->objectFingerprint)) {
            $this->objectFingerprint = new ObjectFingerprintEmbeddable();
        }

        return $this->objectFingerprint;
    }

    public function getObjectHash(): ?string
    {
        return $this->objectFingerprintEmbeddable()->getObjectHash();
    }

    public function setObjectHash(?string $objectHash): void
    {
        $this->objectFingerprintEmbeddable()->setObjectHash($objectHash);
    }

    public function getObjectChecksum(): ?string
    {
        return $this->objectFingerprintEmbeddable()->getObjectChecksum();
    }

    public function setObjectChecksum(?string $objectChecksum): void
    {
        $this->objectFingerprintEmbeddable()->setObjectChecksum($objectChecksum);
    }

    public function getObjectAlgorithm(): ?string
    {
        return $this->objectFingerprintEmbeddable()->getObjectAlgorithm();
    }

    public function setObjectAlgorithm(?string $objectAlgorithm): void
    {
        $this->objectFingerprintEmbeddable()->setObjectAlgorithm($objectAlgorithm);
    }
}
