<?php

declare(strict_types=1);

namespace App\Objecting\EntityInterface;

interface ObjectScopedInterface
{
    public function getObjectScope(): ?string;

    public function setObjectScope(?string $objectScope): void;

    public function getObjectTenant(): ?string;

    public function setObjectTenant(?string $objectTenant): void;

    public function getObjectOrganization(): ?string;

    public function setObjectOrganization(?string $objectOrganization): void;

    public function getObjectOwner(): ?string;

    public function setObjectOwner(?string $objectOwner): void;
}
