<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectCodeEmbeddable
{
    #[ORM\Column(name: 'code', type: 'string', length: 190, nullable: true)]
    private ?string $code = null;

    public function __construct(?string $objectCode = null)
    {
        $this->code = $objectCode;
    }

    public function getObjectCode(): ?string
    {
        return $this->code;
    }

    public function setObjectCode(?string $objectCode): void
    {
        $this->code = $objectCode;
    }
}
