<?php

declare(strict_types=1);

namespace App\Objecting\Factory;

use App\Objecting\Embeddable\ObjectAuditEmbeddable;
use App\Objecting\Embeddable\ObjectIdentityEmbeddable;
use App\Objecting\Embeddable\ObjectTitleEmbeddable;

final class ObjectEmbeddableFactory
{
    public function identity(?string $uuid = null, ?string $slug = null): ObjectIdentityEmbeddable
    {
        return new ObjectIdentityEmbeddable($uuid, $slug);
    }

    public function audit(?\DateTimeImmutable $createdAt = null, ?string $createdBy = null): ObjectAuditEmbeddable
    {
        return new ObjectAuditEmbeddable($createdAt, $createdBy);
    }

    public function title(?string $firstTitle = null, ?string $middleTitle = null, ?string $lastTitle = null): ObjectTitleEmbeddable
    {
        return new ObjectTitleEmbeddable($firstTitle, $middleTitle, $lastTitle);
    }
}
