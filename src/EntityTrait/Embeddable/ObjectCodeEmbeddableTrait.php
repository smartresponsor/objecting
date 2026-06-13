<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use Doctrine\ORM\Mapping as ORM;

trait ObjectCodeEmbeddableTrait
{
    #[ORM\Embedded(class: \App\Objecting\Embeddable\ObjectCodeEmbeddable::class, columnPrefix: false)]
    private \App\Objecting\Embeddable\ObjectCodeEmbeddable $objectCode;

    protected function initializeObjectCode(?string $objectCode = null): void
    {
        $this->objectCode = new \App\Objecting\Embeddable\ObjectCodeEmbeddable($objectCode);
    }

    private function objectCodeEmbeddable(): \App\Objecting\Embeddable\ObjectCodeEmbeddable
    {
        if (!isset($this->objectCode)) {
            $this->objectCode = new \App\Objecting\Embeddable\ObjectCodeEmbeddable();
        }

        return $this->objectCode;
    }

    public function getObjectCode(): ?string
    {
        return $this->objectCodeEmbeddable()->getObjectCode();
    }

    public function setObjectCode(?string $objectCode): void
    {
        $this->objectCodeEmbeddable()->setObjectCode($objectCode);
    }
}
