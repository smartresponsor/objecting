<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use App\Objecting\Embeddable\ObjectVersionEmbeddable;
use Doctrine\ORM\Mapping as ORM;

trait ObjectVersionEmbeddableTrait
{
    #[ORM\Embedded(class: ObjectVersionEmbeddable::class, columnPrefix: false)]
    private ObjectVersionEmbeddable $objectVersion;

    protected function initializeObjectVersion(): void
    {
        $this->objectVersion = new ObjectVersionEmbeddable();
    }

    private function objectVersionEmbeddable(): ObjectVersionEmbeddable
    {
        if (!isset($this->objectVersion)) {
            $this->objectVersion = new ObjectVersionEmbeddable();
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
