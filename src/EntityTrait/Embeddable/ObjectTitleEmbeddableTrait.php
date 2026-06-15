<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use App\Objecting\Embeddable\ObjectTitleEmbeddable;
use Doctrine\ORM\Mapping as ORM;

trait ObjectTitleEmbeddableTrait
{
    #[ORM\Embedded(class: ObjectTitleEmbeddable::class, columnPrefix: false)]
    private ObjectTitleEmbeddable $objectTitle;

    protected function initializeObjectTitle(?string $firstTitle = null, ?string $middleTitle = null, ?string $lastTitle = null): void
    {
        $this->objectTitle = new ObjectTitleEmbeddable($firstTitle, $middleTitle, $lastTitle);
    }

    private function objectTitleEmbeddable(): ObjectTitleEmbeddable
    {
        if (!isset($this->objectTitle)) {
            $this->objectTitle = new ObjectTitleEmbeddable();
        }

        return $this->objectTitle;
    }

    public function getFirstTitle(): ?string
    {
        return $this->objectTitleEmbeddable()->getFirstTitle();
    }

    public function setFirstTitle(?string $firstTitle): void
    {
        $this->objectTitleEmbeddable()->setFirstTitle($firstTitle);
    }

    public function getMiddleTitle(): ?string
    {
        return $this->objectTitleEmbeddable()->getMiddleTitle();
    }

    public function setMiddleTitle(?string $middleTitle): void
    {
        $this->objectTitleEmbeddable()->setMiddleTitle($middleTitle);
    }

    public function getLastTitle(): ?string
    {
        return $this->objectTitleEmbeddable()->getLastTitle();
    }

    public function setLastTitle(?string $lastTitle): void
    {
        $this->objectTitleEmbeddable()->setLastTitle($lastTitle);
    }
}
