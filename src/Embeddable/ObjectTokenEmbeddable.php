<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectTokenEmbeddable
{
    #[ORM\Column(name: 'object_token', type: 'string', length: 255, nullable: true)]
    private ?string $objectToken = null;

    #[ORM\Column(name: 'object_token_expires_at', type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $objectTokenExpiresAt = null;

    public function __construct(?string $objectToken = null, ?\DateTimeImmutable $objectTokenExpiresAt = null)
    {
        $this->objectToken = $objectToken;
        $this->objectTokenExpiresAt = $objectTokenExpiresAt;
    }

    public function getObjectToken(): ?string
    {
        return $this->objectToken;
    }

    public function setObjectToken(?string $objectToken): void
    {
        $this->objectToken = $objectToken;
    }

    public function getObjectTokenExpiresAt(): ?\DateTimeImmutable
    {
        return $this->objectTokenExpiresAt;
    }

    public function setObjectTokenExpiresAt(?\DateTimeImmutable $objectTokenExpiresAt): void
    {
        $this->objectTokenExpiresAt = $objectTokenExpiresAt;
    }

    public function isObjectTokenValid(?\DateTimeImmutable $at = null): bool
    {
        $now = $at ?? new \DateTimeImmutable('now');

        return null !== $this->objectToken && (null === $this->objectTokenExpiresAt || $this->objectTokenExpiresAt > $now);
    }
}
