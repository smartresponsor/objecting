<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Embeddable]
final class ObjectIdentityEmbeddable
{
    #[ORM\Column(name: 'uuid', type: 'binary', length: 16, unique: true, options: ['fixed' => true])]
    private string $uuid;

    #[ORM\Column(name: 'slug', type: 'string', length: 190, unique: true)]
    private string $slug;

    public function __construct(?string $objectUuid = null, ?string $objectSlug = null)
    {
        $uuid = null === $objectUuid ? Uuid::v7() : Uuid::fromString($objectUuid);
        $this->uuid = $uuid->toBinary();
        $this->slug = $objectSlug ?? $uuid->toBase32();
    }

    public function getObjectUuid(): string
    {
        return Uuid::fromString($this->uuid)->toBase32();
    }

    public function getObjectSlug(): string
    {
        return $this->slug;
    }

    public function setObjectSlug(string $objectSlug): void
    {
        $this->slug = $objectSlug;
    }
}
