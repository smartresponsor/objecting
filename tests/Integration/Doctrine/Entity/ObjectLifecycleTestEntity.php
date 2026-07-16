<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Integration\Doctrine\Entity;

use App\Objecting\EntityTrait\Embeddable\ObjectAuditEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectIdentityEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectLockEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectPublicationEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectSoftDeleteEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectTitleEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectVersionEmbeddableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'object_lifecycle_test')]
final class ObjectLifecycleTestEntity
{
    use ObjectAuditEmbeddableTrait;
    use ObjectIdentityEmbeddableTrait;
    use ObjectLockEmbeddableTrait;
    use ObjectPublicationEmbeddableTrait;
    use ObjectSoftDeleteEmbeddableTrait;
    use ObjectTitleEmbeddableTrait;
    use ObjectVersionEmbeddableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    public function __construct(
        ?string $firstTitle = null,
        ?string $slug = null,
        ?string $createdBy = null,
        ?\DateTimeImmutable $createdAt = null,
    ) {
        $this->initializeObjectIdentity(null, $slug);
        $this->initializeObjectAudit($createdAt, $createdBy);
        $this->initializeObjectTitle($firstTitle);
        $this->initializeObjectPublication();
        $this->initializeObjectSoftDelete();
        $this->initializeObjectVersion();
        $this->initializeObjectLock();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
