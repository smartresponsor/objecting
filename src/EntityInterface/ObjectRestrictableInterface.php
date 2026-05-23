<?php

declare(strict_types=1);

namespace App\Objecting\EntityInterface;

interface ObjectRestrictableInterface
{
    /** @return list<string> */
    public function getObjectAllowedRoles(): array;

    /** @param list<string> $objectAllowedRoles */
    public function setObjectAllowedRoles(array $objectAllowedRoles): void;

    /** @return list<string> */
    public function getObjectIpWhitelist(): array;

    /** @param list<string> $objectIpWhitelist */
    public function setObjectIpWhitelist(array $objectIpWhitelist): void;

    public function isObjectAccessAllowed(?string $role, ?string $ip): bool;
}
