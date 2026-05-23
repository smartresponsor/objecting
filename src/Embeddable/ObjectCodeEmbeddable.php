<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectCodeEmbeddable
{
    #[ORM\Column(name: 'object_code', type: 'string', length: 190, nullable: true)]
    private ?string $objectCode = null;

    public function __construct(?string $objectCode = null)
    {
        $this->objectCode = $objectCode;
    }

    public function getObjectCode(): ?string
    {
        return $this->objectCode;
    }

    public function setObjectCode(?string $objectCode): void
    {
        $this->objectCode = $objectCode;
    }
}
