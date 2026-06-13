<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use Doctrine\ORM\Mapping as ORM;

trait ObjectIdentityEmbeddableTrait
{
    #[ORM\Embedded(class: \App\Objecting\Embeddable\ObjectIdentityEmbeddable::class, columnPrefix: false)]
    private \App\Objecting\Embeddable\ObjectIdentityEmbeddable $objectIdentity;

    protected function initializeObjectIdentity(?string $objectUuid = null, ?string $objectSlug = null): void
    {
        $this->objectIdentity = new \App\Objecting\Embeddable\ObjectIdentityEmbeddable($objectUuid, $objectSlug);
    }

    private function objectIdentityEmbeddable(): \App\Objecting\Embeddable\ObjectIdentityEmbeddable
    {
        if (!isset($this->objectIdentity)) {
            $this->objectIdentity = new \App\Objecting\Embeddable\ObjectIdentityEmbeddable();
        }

        return $this->objectIdentity;
    }

    public function getObjectUuid(): string
    {
        return $this->objectIdentityEmbeddable()->getObjectUuid();
    }

    public function getObjectSlug(): ?string
    {
        return $this->objectIdentityEmbeddable()->getObjectSlug();
    }

    public function setObjectSlug(?string $objectSlug): void
    {
        $this->objectIdentityEmbeddable()->setObjectSlug($objectSlug);
    }
}
