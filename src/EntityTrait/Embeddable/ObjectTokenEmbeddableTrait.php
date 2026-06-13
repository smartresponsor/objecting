<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use Doctrine\ORM\Mapping as ORM;

trait ObjectTokenEmbeddableTrait
{
    #[ORM\Embedded(class: \App\Objecting\Embeddable\ObjectTokenEmbeddable::class, columnPrefix: false)]
    private \App\Objecting\Embeddable\ObjectTokenEmbeddable $objectToken;

    protected function initializeObjectToken(?string $objectToken = null, ?\DateTimeImmutable $objectTokenExpiresAt = null): void
    {
        $this->objectToken = new \App\Objecting\Embeddable\ObjectTokenEmbeddable($objectToken, $objectTokenExpiresAt);
    }

    private function objectTokenEmbeddable(): \App\Objecting\Embeddable\ObjectTokenEmbeddable
    {
        if (!isset($this->objectToken)) {
            $this->objectToken = new \App\Objecting\Embeddable\ObjectTokenEmbeddable();
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
