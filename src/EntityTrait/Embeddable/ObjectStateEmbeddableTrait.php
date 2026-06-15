<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use App\Objecting\Embeddable\ObjectStateEmbeddable;
use Doctrine\ORM\Mapping as ORM;

trait ObjectStateEmbeddableTrait
{
    #[ORM\Embedded(class: ObjectStateEmbeddable::class, columnPrefix: false)]
    private ObjectStateEmbeddable $objectState;

    protected function initializeObjectState(bool $objectActive = true, bool $objectEnabled = true, ?string $objectStatus = null): void
    {
        $this->objectState = new ObjectStateEmbeddable($objectActive, $objectEnabled, $objectStatus);
    }

    private function objectStateEmbeddable(): ObjectStateEmbeddable
    {
        if (!isset($this->objectState)) {
            $this->objectState = new ObjectStateEmbeddable();
        }

        return $this->objectState;
    }

    public function isObjectActive(): bool
    {
        return $this->objectStateEmbeddable()->isObjectActive();
    }

    public function setObjectActive(bool $objectActive): void
    {
        $this->objectStateEmbeddable()->setObjectActive($objectActive);
    }

    public function isObjectEnabled(): bool
    {
        return $this->objectStateEmbeddable()->isObjectEnabled();
    }

    public function setObjectEnabled(bool $objectEnabled): void
    {
        $this->objectStateEmbeddable()->setObjectEnabled($objectEnabled);
    }

    public function getObjectStatus(): ?string
    {
        return $this->objectStateEmbeddable()->getObjectStatus();
    }

    public function setObjectStatus(?string $objectStatus): void
    {
        $this->objectStateEmbeddable()->setObjectStatus($objectStatus);
    }
}
