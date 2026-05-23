<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectVersionEmbeddable
{
    #[ORM\Column(name: 'object_version', type: 'integer')]
    private int $objectVersion = 1;

    #[ORM\Column(name: 'object_etag', type: 'string', length: 128, nullable: true)]
    private ?string $objectEtag = null;

    public function getObjectVersion(): int
    {
        return $this->objectVersion;
    }

    public function getObjectEtag(): ?string
    {
        return $this->objectEtag;
    }

    public function bumpObjectVersion(?string $objectEtag = null): void
    {
        ++$this->objectVersion;
        $this->objectEtag = $objectEtag;
    }
}
