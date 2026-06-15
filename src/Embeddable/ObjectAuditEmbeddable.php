<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectAuditEmbeddable
{
    #[ORM\Column(name: 'object_created_at', type: 'datetime_immutable')]
    private \DateTimeImmutable $objectCreatedAt;

    #[ORM\Column(name: 'object_modified_at', type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $objectModifiedAt = null;

    /**
     * Canonical cross-system identifier of the Vendor identity that created the object.
     *
     * Objecting stores the identifier as an opaque scalar and does not own the
     * VendorEntity or VendorSecurityEntity association.
     */
    #[ORM\Column(name: 'object_created_by', type: 'string', length: 190, nullable: true)]
    private ?string $objectCreatedBy = null;

    /** Canonical cross-system Vendor identity that last modified the object. */
    #[ORM\Column(name: 'object_modified_by', type: 'string', length: 190, nullable: true)]
    private ?string $objectModifiedBy = null;

    public function __construct(?\DateTimeImmutable $createdAt = null, ?string $createdBy = null)
    {
        $this->objectCreatedAt = $createdAt ?? new \DateTimeImmutable('now');
        $this->objectCreatedBy = $createdBy;
    }

    public function getObjectCreatedAt(): \DateTimeImmutable
    {
        return $this->objectCreatedAt;
    }

    public function getObjectModifiedAt(): ?\DateTimeImmutable
    {
        return $this->objectModifiedAt;
    }

    public function getObjectCreatedBy(): ?string
    {
        return $this->objectCreatedBy;
    }

    public function getObjectModifiedBy(): ?string
    {
        return $this->objectModifiedBy;
    }

    public function touchModified(?\DateTimeImmutable $modifiedAt = null, ?string $modifiedBy = null): void
    {
        $this->objectModifiedAt = $modifiedAt ?? new \DateTimeImmutable('now');
        $this->objectModifiedBy = $modifiedBy;
    }
}
