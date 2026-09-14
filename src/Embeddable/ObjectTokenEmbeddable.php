<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectTokenEmbeddable
{
    #[ORM\Column(name: 'token', type: 'string', length: 255, nullable: true)]
    private ?string $token = null;

    #[ORM\Column(name: 'token_expires_at', type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $tokenExpiresAt = null;

    public function __construct(?string $objectToken = null, ?\DateTimeImmutable $objectTokenExpiresAt = null)
    {
        $this->token = $objectToken;
        $this->tokenExpiresAt = $objectTokenExpiresAt;
    }

    public function getObjectToken(): ?string
    {
        return $this->token;
    }

    public function setObjectToken(?string $objectToken): void
    {
        $this->token = $objectToken;
    }

    public function getObjectTokenExpiresAt(): ?\DateTimeImmutable
    {
        return $this->tokenExpiresAt;
    }

    public function setObjectTokenExpiresAt(?\DateTimeImmutable $objectTokenExpiresAt): void
    {
        $this->tokenExpiresAt = $objectTokenExpiresAt;
    }

    public function isObjectTokenValid(?\DateTimeImmutable $at = null): bool
    {
        $now = $at ?? new \DateTimeImmutable('now');

        return null !== $this->token && (null === $this->tokenExpiresAt || $this->tokenExpiresAt > $now);
    }
}
