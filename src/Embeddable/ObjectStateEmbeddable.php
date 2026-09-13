<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectStateEmbeddable
{
    #[ORM\Column(name: 'object_active', type: 'boolean', options: ['default' => true])]
    private bool $objectActive = true;

    #[ORM\Column(name: 'object_enabled', type: 'boolean', options: ['default' => true])]
    private bool $objectEnabled = true;

    #[ORM\Column(name: 'object_status', type: 'string', length: 64, nullable: true)]
    private ?string $objectStatus = null;

    public function __construct(bool $objectActive = true, bool $objectEnabled = true, ?string $objectStatus = null)
    {
        $this->objectActive = $objectActive;
        $this->objectEnabled = $objectEnabled;
        $this->objectStatus = $objectStatus;
    }

    public function isObjectActive(): bool
    {
        return $this->objectActive;
    }

    public function setObjectActive(bool $objectActive): void
    {
        $this->objectActive = $objectActive;
    }

    public function isObjectEnabled(): bool
    {
        return $this->objectEnabled;
    }

    public function setObjectEnabled(bool $objectEnabled): void
    {
        $this->objectEnabled = $objectEnabled;
    }

    public function getObjectStatus(): ?string
    {
        return $this->objectStatus;
    }

    public function setObjectStatus(?string $objectStatus): void
    {
        $this->objectStatus = $objectStatus;
    }
}
