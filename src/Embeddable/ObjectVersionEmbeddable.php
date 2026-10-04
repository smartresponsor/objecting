<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectVersionEmbeddable
{
    #[ORM\Column(name: 'version', type: 'integer')]
    private int $version = 1;

    #[ORM\Column(name: 'etag', type: 'string', length: 128, nullable: true)]
    private ?string $etag = null;

    public function getObjectVersion(): int
    {
        return $this->version;
    }

    public function getObjectEtag(): ?string
    {
        return $this->etag;
    }

    public function bumpObjectVersion(?string $objectEtag = null): void
    {
        ++$this->version;
        $this->etag = $objectEtag;
    }
}
