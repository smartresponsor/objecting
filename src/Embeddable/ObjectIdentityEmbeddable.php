<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Embeddable]
final class ObjectIdentityEmbeddable
{
    #[ORM\Column(name: 'object_uuid', type: 'string', length: 36, unique: true)]
    private string $objectUuid;

    #[ORM\Column(name: 'object_slug', type: 'string', length: 190, nullable: true)]
    private ?string $objectSlug = null;

    public function __construct(?string $objectUuid = null, ?string $objectSlug = null)
    {
        $this->objectUuid = $objectUuid ?? Uuid::v7()->toRfc4122();
        $this->objectSlug = $objectSlug;
    }

    public function getObjectUuid(): string
    {
        return $this->objectUuid;
    }

    public function setObjectUuid(string $objectUuid): void
    {
        $this->objectUuid = $objectUuid;
    }

    public function getObjectSlug(): ?string
    {
        return $this->objectSlug;
    }

    public function setObjectSlug(?string $objectSlug): void
    {
        $this->objectSlug = $objectSlug;
    }
}
