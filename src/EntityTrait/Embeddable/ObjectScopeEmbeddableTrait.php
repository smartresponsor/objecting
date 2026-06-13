<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use Doctrine\ORM\Mapping as ORM;

trait ObjectScopeEmbeddableTrait
{
    #[ORM\Embedded(class: \App\Objecting\Embeddable\ObjectScopeEmbeddable::class, columnPrefix: false)]
    private \App\Objecting\Embeddable\ObjectScopeEmbeddable $objectScope;

    protected function initializeObjectScope(?string $objectScope = null, ?string $objectTenant = null, ?string $objectOrganization = null, ?string $objectOwner = null): void
    {
        $this->objectScope = new \App\Objecting\Embeddable\ObjectScopeEmbeddable($objectScope, $objectTenant, $objectOrganization, $objectOwner);
    }

    private function objectScopeEmbeddable(): \App\Objecting\Embeddable\ObjectScopeEmbeddable
    {
        if (!isset($this->objectScope)) {
            $this->objectScope = new \App\Objecting\Embeddable\ObjectScopeEmbeddable();
        }

        return $this->objectScope;
    }

    public function getObjectScope(): ?string
    {
        return $this->objectScopeEmbeddable()->getObjectScope();
    }

    public function setObjectScope(?string $objectScope): void
    {
        $this->objectScopeEmbeddable()->setObjectScope($objectScope);
    }

    public function getObjectTenant(): ?string
    {
        return $this->objectScopeEmbeddable()->getObjectTenant();
    }

    public function setObjectTenant(?string $objectTenant): void
    {
        $this->objectScopeEmbeddable()->setObjectTenant($objectTenant);
    }

    public function getObjectOrganization(): ?string
    {
        return $this->objectScopeEmbeddable()->getObjectOrganization();
    }

    public function setObjectOrganization(?string $objectOrganization): void
    {
        $this->objectScopeEmbeddable()->setObjectOrganization($objectOrganization);
    }

    public function getObjectOwner(): ?string
    {
        return $this->objectScopeEmbeddable()->getObjectOwner();
    }

    public function setObjectOwner(?string $objectOwner): void
    {
        $this->objectScopeEmbeddable()->setObjectOwner($objectOwner);
    }
}
