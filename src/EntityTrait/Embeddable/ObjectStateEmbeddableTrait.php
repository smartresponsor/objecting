<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use Doctrine\ORM\Mapping as ORM;

trait ObjectStateEmbeddableTrait
{
    #[ORM\Embedded(class: \App\Objecting\Embeddable\ObjectStateEmbeddable::class, columnPrefix: false)]
    private \App\Objecting\Embeddable\ObjectStateEmbeddable $objectState;

    protected function initializeObjectState(bool $objectActive = true, bool $objectEnabled = true, ?string $objectStatus = null): void
    {
        $this->objectState = new \App\Objecting\Embeddable\ObjectStateEmbeddable($objectActive, $objectEnabled, $objectStatus);
    }

    private function objectStateEmbeddable(): \App\Objecting\Embeddable\ObjectStateEmbeddable
    {
        if (!isset($this->objectState)) {
            $this->objectState = new \App\Objecting\Embeddable\ObjectStateEmbeddable();
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
