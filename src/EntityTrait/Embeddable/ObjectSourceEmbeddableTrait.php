<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use Doctrine\ORM\Mapping as ORM;

trait ObjectSourceEmbeddableTrait
{
    #[ORM\Embedded(class: \App\Objecting\Embeddable\ObjectSourceEmbeddable::class, columnPrefix: false)]
    private \App\Objecting\Embeddable\ObjectSourceEmbeddable $objectSource;

    protected function initializeObjectSource(?string $objectSource = null, ?string $objectProvider = null, ?string $objectExternalId = null, ?string $objectSourceType = null): void
    {
        $this->objectSource = new \App\Objecting\Embeddable\ObjectSourceEmbeddable($objectSource, $objectProvider, $objectExternalId, $objectSourceType);
    }

    private function objectSourceEmbeddable(): \App\Objecting\Embeddable\ObjectSourceEmbeddable
    {
        if (!isset($this->objectSource)) {
            $this->objectSource = new \App\Objecting\Embeddable\ObjectSourceEmbeddable();
        }

        return $this->objectSource;
    }

    public function getObjectSource(): ?string
    {
        return $this->objectSourceEmbeddable()->getObjectSource();
    }

    public function setObjectSource(?string $objectSource): void
    {
        $this->objectSourceEmbeddable()->setObjectSource($objectSource);
    }

    public function getObjectProvider(): ?string
    {
        return $this->objectSourceEmbeddable()->getObjectProvider();
    }

    public function setObjectProvider(?string $objectProvider): void
    {
        $this->objectSourceEmbeddable()->setObjectProvider($objectProvider);
    }

    public function getObjectExternalId(): ?string
    {
        return $this->objectSourceEmbeddable()->getObjectExternalId();
    }

    public function setObjectExternalId(?string $objectExternalId): void
    {
        $this->objectSourceEmbeddable()->setObjectExternalId($objectExternalId);
    }

    public function getObjectSourceType(): ?string
    {
        return $this->objectSourceEmbeddable()->getObjectSourceType();
    }

    public function setObjectSourceType(?string $objectSourceType): void
    {
        $this->objectSourceEmbeddable()->setObjectSourceType($objectSourceType);
    }
}
