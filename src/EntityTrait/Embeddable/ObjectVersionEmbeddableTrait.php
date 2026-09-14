<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use Doctrine\ORM\Mapping as ORM;

trait ObjectVersionEmbeddableTrait
{
    #[ORM\Version]
    #[ORM\Column(name: 'version', type: 'integer')]
    private int $objectVersion = 1;

    #[ORM\Column(name: 'etag', type: 'string', length: 128, nullable: true)]
    private ?string $objectEtag = null;

    protected function initializeObjectVersion(): void
    {
        $this->objectVersion = 1;
        $this->objectEtag = null;
    }

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
        $this->objectEtag = $objectEtag;
    }
}
