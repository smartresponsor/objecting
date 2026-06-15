<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use App\Objecting\Embeddable\ObjectIdentityEmbeddable;
use Doctrine\ORM\Mapping as ORM;

trait ObjectIdentityEmbeddableTrait
{
    #[ORM\Embedded(class: ObjectIdentityEmbeddable::class, columnPrefix: false)]
    private ObjectIdentityEmbeddable $objectIdentity;

    protected function initializeObjectIdentity(?string $objectUuid = null, ?string $objectSlug = null): void
    {
        $this->objectIdentity = new ObjectIdentityEmbeddable($objectUuid, $objectSlug);
    }

    private function objectIdentityEmbeddable(): ObjectIdentityEmbeddable
    {
        if (!isset($this->objectIdentity)) {
            $this->objectIdentity = new ObjectIdentityEmbeddable();
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
