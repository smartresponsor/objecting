<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use App\Objecting\Embeddable\ObjectPublicationEmbeddable;
use Doctrine\ORM\Mapping as ORM;

trait ObjectPublicationEmbeddableTrait
{
    #[ORM\Embedded(class: ObjectPublicationEmbeddable::class, columnPrefix: false)]
    private ObjectPublicationEmbeddable $objectPublication;

    protected function initializeObjectPublication(): void
    {
        $this->objectPublication = new ObjectPublicationEmbeddable();
    }

    private function objectPublicationEmbeddable(): ObjectPublicationEmbeddable
    {
        if (!isset($this->objectPublication)) {
            $this->objectPublication = new ObjectPublicationEmbeddable();
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
