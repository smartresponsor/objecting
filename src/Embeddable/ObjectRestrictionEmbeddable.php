<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectRestrictionEmbeddable
{
    /** @var list<string> */
    #[ORM\Column(name: 'object_allowed_roles', type: 'json')]
    private array $objectAllowedRoles = [];

    /** @var list<string> */
    #[ORM\Column(name: 'object_ip_whitelist', type: 'json')]
    private array $objectIpWhitelist = [];

    /** @return list<string> */
    public function getObjectAllowedRoles(): array
    {
        return $this->objectAllowedRoles;
    }

    /** @param list<string> $objectAllowedRoles */
    public function setObjectAllowedRoles(array $objectAllowedRoles): void
    {
        $this->objectAllowedRoles = array_values(array_filter($objectAllowedRoles, 'is_string'));
    }

    /** @return list<string> */
    public function getObjectIpWhitelist(): array
    {
        return $this->objectIpWhitelist;
    }

    /** @param list<string> $objectIpWhitelist */
    public function setObjectIpWhitelist(array $objectIpWhitelist): void
    {
        $this->objectIpWhitelist = array_values(array_filter($objectIpWhitelist, 'is_string'));
    }

    public function isObjectAccessAllowed(?string $role, ?string $ip): bool
    {
        $roleAllowed = [] === $this->objectAllowedRoles || (null !== $role && in_array($role, $this->objectAllowedRoles, true));
        $ipAllowed = [] === $this->objectIpWhitelist || (null !== $ip && in_array($ip, $this->objectIpWhitelist, true));

        return $roleAllowed && $ipAllowed;
    }
}
