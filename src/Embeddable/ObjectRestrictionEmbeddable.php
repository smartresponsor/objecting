<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectRestrictionEmbeddable
{
    /** @var list<string> */
    #[ORM\Column(name: 'allowed_roles', type: 'json')]
    private array $allowedRoles = [];

    /** @var list<string> */
    #[ORM\Column(name: 'ip_whitelist', type: 'json')]
    private array $ipWhitelist = [];

    /** @return list<string> */
    public function getObjectAllowedRoles(): array
    {
        return $this->allowedRoles;
    }

    /** @param list<string> $objectAllowedRoles */
    public function setObjectAllowedRoles(array $objectAllowedRoles): void
    {
        $this->allowedRoles = array_values(array_filter($objectAllowedRoles, 'is_string'));
    }

    /** @return list<string> */
    public function getObjectIpWhitelist(): array
    {
        return $this->ipWhitelist;
    }

    /** @param list<string> $objectIpWhitelist */
    public function setObjectIpWhitelist(array $objectIpWhitelist): void
    {
        $this->ipWhitelist = array_values(array_filter($objectIpWhitelist, 'is_string'));
    }

    public function isObjectAccessAllowed(?string $role, ?string $ip): bool
    {
        $roleAllowed = [] === $this->allowedRoles || (null !== $role && in_array($role, $this->allowedRoles, true));
        $ipAllowed = [] === $this->ipWhitelist || (null !== $ip && in_array($ip, $this->ipWhitelist, true));

        return $roleAllowed && $ipAllowed;
    }
}
