<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use App\Objecting\Embeddable\ObjectTokenEmbeddable;
use Doctrine\ORM\Mapping as ORM;

trait ObjectTokenEmbeddableTrait
{
    #[ORM\Embedded(class: ObjectTokenEmbeddable::class, columnPrefix: false)]
    private ObjectTokenEmbeddable $objectToken;

    protected function initializeObjectToken(?string $objectToken = null, ?\DateTimeImmutable $objectTokenExpiresAt = null): void
    {
        $this->objectToken = new ObjectTokenEmbeddable($objectToken, $objectTokenExpiresAt);
    }

    private function objectTokenEmbeddable(): ObjectTokenEmbeddable
    {
        if (!isset($this->objectToken)) {
            $this->objectToken = new ObjectTokenEmbeddable();
        }

        return $this->objectToken;
    }

    public function getObjectToken(): ?string
    {
        return $this->objectTokenEmbeddable()->getObjectToken();
    }

    public function setObjectToken(?string $objectToken): void
    {
        $this->objectTokenEmbeddable()->setObjectToken($objectToken);
    }

    public function isObjectTokenValid(?\DateTimeImmutable $at = null): bool
    {
        return $this->objectTokenEmbeddable()->isObjectTokenValid($at);
    }
}
