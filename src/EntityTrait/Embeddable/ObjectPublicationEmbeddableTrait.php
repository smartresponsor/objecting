<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use Doctrine\ORM\Mapping as ORM;

trait ObjectPublicationEmbeddableTrait
{
    #[ORM\Embedded(class: \App\Objecting\Embeddable\ObjectPublicationEmbeddable::class, columnPrefix: false)]
    private \App\Objecting\Embeddable\ObjectPublicationEmbeddable $objectPublication;

    protected function initializeObjectPublication(): void
    {
        $this->objectPublication = new \App\Objecting\Embeddable\ObjectPublicationEmbeddable();
    }

    private function objectPublicationEmbeddable(): \App\Objecting\Embeddable\ObjectPublicationEmbeddable
    {
        if (!isset($this->objectPublication)) {
            $this->objectPublication = new \App\Objecting\Embeddable\ObjectPublicationEmbeddable();
        }

        return $this->objectPublication;
    }

    public function isObjectPublished(): bool
    {
        return $this->objectPublicationEmbeddable()->isObjectPublished();
    }

    public function getObjectPublishedAt(): ?\DateTimeImmutable
    {
        return $this->objectPublicationEmbeddable()->getObjectPublishedAt();
    }

    public function publishObject(?\DateTimeImmutable $publishedAt = null): void
    {
        $this->objectPublicationEmbeddable()->publish($publishedAt);
    }

    public function unpublishObject(): void
    {
        $this->objectPublicationEmbeddable()->unpublish();
    }
}
