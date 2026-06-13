<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use Doctrine\ORM\Mapping as ORM;

trait ObjectRestrictionEmbeddableTrait
{
    #[ORM\Embedded(class: \App\Objecting\Embeddable\ObjectRestrictionEmbeddable::class, columnPrefix: false)]
    private \App\Objecting\Embeddable\ObjectRestrictionEmbeddable $objectRestriction;

    protected function initializeObjectRestriction(): void
    {
        $this->objectRestriction = new \App\Objecting\Embeddable\ObjectRestrictionEmbeddable();
    }

    private function objectRestrictionEmbeddable(): \App\Objecting\Embeddable\ObjectRestrictionEmbeddable
    {
        if (!isset($this->objectRestriction)) {
            $this->objectRestriction = new \App\Objecting\Embeddable\ObjectRestrictionEmbeddable();
        }

        return $this->objectRestriction;
    }

    /** @return list<string> */
    public function getObjectAllowedRoles(): array
    {
        return $this->objectRestrictionEmbeddable()->getObjectAllowedRoles();
    }

    /** @param list<string> $objectAllowedRoles */
    public function setObjectAllowedRoles(array $objectAllowedRoles): void
    {
        $this->objectRestrictionEmbeddable()->setObjectAllowedRoles($objectAllowedRoles);
    }

    /** @return list<string> */
    public function getObjectIpWhitelist(): array
    {
        return $this->objectRestrictionEmbeddable()->getObjectIpWhitelist();
    }

    /** @param list<string> $objectIpWhitelist */
    public function setObjectIpWhitelist(array $objectIpWhitelist): void
    {
        $this->objectRestrictionEmbeddable()->setObjectIpWhitelist($objectIpWhitelist);
    }

    public function isObjectAccessAllowed(?string $role, ?string $ip): bool
    {
        return $this->objectRestrictionEmbeddable()->isObjectAccessAllowed($role, $ip);
    }
}
