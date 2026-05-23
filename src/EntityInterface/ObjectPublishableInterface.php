<?php

declare(strict_types=1);

namespace App\Objecting\EntityInterface;

interface ObjectPublishableInterface
{
    public function isObjectPublished(): bool;

    public function getObjectPublishedAt(): ?\DateTimeImmutable;

    public function publishObject(?\DateTimeImmutable $publishedAt = null): void;

    public function unpublishObject(): void;
}
