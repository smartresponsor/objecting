<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectConfigEmbeddable
{
    /** @var array<string, mixed> */
    #[ORM\Column(name: 'config', type: 'json')]
    private array $config = [];

    /** @return array<string, mixed> */
    public function getObjectConfig(): array
    {
        return $this->config;
    }

    /** @param array<string, mixed> $objectConfig */
    public function setObjectConfig(array $objectConfig): void
    {
        $this->config = $objectConfig;
    }
}
