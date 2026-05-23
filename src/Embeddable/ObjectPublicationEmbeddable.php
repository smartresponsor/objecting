<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectPublicationEmbeddable
{
    #[ORM\Column(name: 'object_published', type: 'boolean')]
    private bool $objectPublished = false;

    #[ORM\Column(name: 'object_published_at', type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $objectPublishedAt = null;

    public function isObjectPublished(): bool
    {
        return $this->objectPublished;
    }

    public function getObjectPublishedAt(): ?\DateTimeImmutable
    {
        return $this->objectPublishedAt;
    }

    public function publish(?\DateTimeImmutable $publishedAt = null): void
    {
        $this->objectPublished = true;
        $this->objectPublishedAt = $publishedAt ?? new \DateTimeImmutable('now');
    }

    public function unpublish(): void
    {
        $this->objectPublished = false;
        $this->objectPublishedAt = null;
    }
}
