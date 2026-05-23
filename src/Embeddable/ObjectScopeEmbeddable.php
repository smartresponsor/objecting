<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectScopeEmbeddable
{
    #[ORM\Column(name: 'object_scope', type: 'string', length: 190, nullable: true)]
    private ?string $objectScope = null;

    #[ORM\Column(name: 'object_tenant', type: 'string', length: 190, nullable: true)]
    private ?string $objectTenant = null;

    #[ORM\Column(name: 'object_organization', type: 'string', length: 190, nullable: true)]
    private ?string $objectOrganization = null;

    #[ORM\Column(name: 'object_owner', type: 'string', length: 190, nullable: true)]
    private ?string $objectOwner = null;

    public function __construct(?string $objectScope = null, ?string $objectTenant = null, ?string $objectOrganization = null, ?string $objectOwner = null)
    {
        $this->objectScope = $objectScope;
        $this->objectTenant = $objectTenant;
        $this->objectOrganization = $objectOrganization;
        $this->objectOwner = $objectOwner;
    }

    public function getObjectScope(): ?string
    {
        return $this->objectScope;
    }

    public function setObjectScope(?string $objectScope): void
    {
        $this->objectScope = $objectScope;
    }

    public function getObjectTenant(): ?string
    {
        return $this->objectTenant;
    }

    public function setObjectTenant(?string $objectTenant): void
    {
        $this->objectTenant = $objectTenant;
    }

    public function getObjectOrganization(): ?string
    {
        return $this->objectOrganization;
    }

    public function setObjectOrganization(?string $objectOrganization): void
    {
        $this->objectOrganization = $objectOrganization;
    }

    public function getObjectOwner(): ?string
    {
        return $this->objectOwner;
    }

    public function setObjectOwner(?string $objectOwner): void
    {
        $this->objectOwner = $objectOwner;
    }
}
