<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use App\Objecting\Embeddable\ObjectCodeEmbeddable;
use Doctrine\ORM\Mapping as ORM;

trait ObjectCodeEmbeddableTrait
{
    #[ORM\Embedded(class: ObjectCodeEmbeddable::class, columnPrefix: false)]
    private ObjectCodeEmbeddable $objectCode;

    protected function initializeObjectCode(?string $objectCode = null): void
    {
        $this->objectCode = new ObjectCodeEmbeddable($objectCode);
    }

    private function objectCodeEmbeddable(): ObjectCodeEmbeddable
    {
        if (!isset($this->objectCode)) {
            $this->objectCode = new ObjectCodeEmbeddable();
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
