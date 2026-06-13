<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use Doctrine\ORM\Mapping as ORM;

trait ObjectVersionEmbeddableTrait
{
    #[ORM\Embedded(class: \App\Objecting\Embeddable\ObjectVersionEmbeddable::class, columnPrefix: false)]
    private \App\Objecting\Embeddable\ObjectVersionEmbeddable $objectVersion;

    protected function initializeObjectVersion(): void
    {
        $this->objectVersion = new \App\Objecting\Embeddable\ObjectVersionEmbeddable();
    }

    private function objectVersionEmbeddable(): \App\Objecting\Embeddable\ObjectVersionEmbeddable
    {
        if (!isset($this->objectVersion)) {
            $this->objectVersion = new \App\Objecting\Embeddable\ObjectVersionEmbeddable();
        }

        return $this->objectVersion;
    }

    public function getObjectVersion(): int
    {
        return $this->objectVersionEmbeddable()->getObjectVersion();
    }

    public function getObjectEtag(): ?string
    {
        return $this->objectVersionEmbeddable()->getObjectEtag();
    }

    public function bumpObjectVersion(?string $objectEtag = null): void
    {
        $this->objectVersionEmbeddable()->bumpObjectVersion($objectEtag);
    }
}
