<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use Doctrine\ORM\Mapping as ORM;

trait ObjectConfigEmbeddableTrait
{
    #[ORM\Embedded(class: \App\Objecting\Embeddable\ObjectConfigEmbeddable::class, columnPrefix: false)]
    private \App\Objecting\Embeddable\ObjectConfigEmbeddable $objectConfig;

    protected function initializeObjectConfig(): void
    {
        $this->objectConfig = new \App\Objecting\Embeddable\ObjectConfigEmbeddable();
    }

    private function objectConfigEmbeddable(): \App\Objecting\Embeddable\ObjectConfigEmbeddable
    {
        if (!isset($this->objectConfig)) {
            $this->objectConfig = new \App\Objecting\Embeddable\ObjectConfigEmbeddable();
        }

        return $this->objectConfig;
    }

    /** @return array<string, mixed> */
    public function getObjectConfig(): array
    {
        return $this->objectConfigEmbeddable()->getObjectConfig();
    }

    /** @param array<string, mixed> $objectConfig */
    public function setObjectConfig(array $objectConfig): void
    {
        $this->objectConfigEmbeddable()->setObjectConfig($objectConfig);
    }
}
