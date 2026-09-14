<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectStateEmbeddable
{
    #[ORM\Column(name: 'active', type: 'boolean', options: ['default' => true])]
    private bool $active = true;

    #[ORM\Column(name: 'enabled', type: 'boolean', options: ['default' => true])]
    private bool $enabled = true;

    #[ORM\Column(name: 'status', type: 'string', length: 64, nullable: true)]
    private ?string $status = null;

    public function __construct(bool $objectActive = true, bool $objectEnabled = true, ?string $objectStatus = null)
    {
        $this->active = $objectActive;
        $this->enabled = $objectEnabled;
        $this->status = $objectStatus;
    }

    public function isObjectActive(): bool
    {
        return $this->active;
    }

    public function setObjectActive(bool $objectActive): void
    {
        $this->active = $objectActive;
    }

    public function isObjectEnabled(): bool
    {
        return $this->enabled;
    }

    public function setObjectEnabled(bool $objectEnabled): void
    {
        $this->enabled = $objectEnabled;
    }

    public function getObjectStatus(): ?string
    {
        return $this->status;
    }

    public function setObjectStatus(?string $objectStatus): void
    {
        $this->status = $objectStatus;
    }
}
