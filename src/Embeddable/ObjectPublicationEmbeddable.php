<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectPublicationEmbeddable
{
    #[ORM\Column(name: 'published', type: 'boolean')]
    private bool $published = false;

    #[ORM\Column(name: 'published_at', type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $publishedAt = null;

    public function isObjectPublished(): bool
    {
        return $this->published;
    }

    public function getObjectPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function publish(?\DateTimeImmutable $publishedAt = null): void
    {
        $this->published = true;
        $this->publishedAt = $publishedAt ?? new \DateTimeImmutable('now');
    }

    public function unpublish(): void
    {
        $this->published = false;
        $this->publishedAt = null;
    }
}
