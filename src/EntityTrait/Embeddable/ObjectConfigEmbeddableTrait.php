<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use App\Objecting\Embeddable\ObjectConfigEmbeddable;
use Doctrine\ORM\Mapping as ORM;

trait ObjectConfigEmbeddableTrait
{
    #[ORM\Embedded(class: ObjectConfigEmbeddable::class, columnPrefix: false)]
    private ObjectConfigEmbeddable $objectConfig;

    protected function initializeObjectConfig(): void
    {
        $this->objectConfig = new ObjectConfigEmbeddable();
    }

    private function objectConfigEmbeddable(): ObjectConfigEmbeddable
    {
        if (!isset($this->objectConfig)) {
            $this->objectConfig = new ObjectConfigEmbeddable();
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
