<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectConfigEmbeddable
{
    /** @var array<string, mixed> */
    #[ORM\Column(name: 'object_config', type: 'json')]
    private array $objectConfig = [];

    /** @return array<string, mixed> */
    public function getObjectConfig(): array
    {
        return $this->objectConfig;
    }

    /** @param array<string, mixed> $objectConfig */
    public function setObjectConfig(array $objectConfig): void
    {
        $this->objectConfig = $objectConfig;
    }
}
