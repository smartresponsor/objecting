<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectAuditEmbeddable
{
    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    private \DateTimeImmutable $objectCreatedAt;

    #[ORM\Column(name: 'modified_at', type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $objectModifiedAt = null;

    /**
     * Canonical cross-system identifier of the Vendor identity that created the object.
     *
     * Objecting stores the identifier as an opaque scalar and does not own the
     * VendorEntity or VendorSecurityEntity association.
     */
    #[ORM\Column(name: 'created_by', type: 'string', length: 190, nullable: true)]
    private ?string $objectCreatedBy = null;

    /** Canonical cross-system Vendor identity that last modified the object. */
    #[ORM\Column(name: 'modified_by', type: 'string', length: 190, nullable: true)]
    private ?string $objectModifiedBy = null;

    public function __construct(?\DateTimeImmutable $createdAt = null, ?string $createdBy = null)
    {
        $this->objectCreatedAt = $createdAt ?? new \DateTimeImmutable('now');
        $this->objectCreatedBy = $createdBy;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->objectCreatedAt;
    }

    public function getModifiedAt(): ?\DateTimeImmutable
    {
        return $this->objectModifiedAt;
    }

    public function getCreatedBy(): ?string
    {
        return $this->objectCreatedBy;
    }

    public function getModifiedBy(): ?string
    {
        return $this->objectModifiedBy;
    }

    public function touchModified(?\DateTimeImmutable $modifiedAt = null, ?string $modifiedBy = null): void
    {
        $effectiveModifiedAt = $modifiedAt ?? new \DateTimeImmutable('now');
        if ($effectiveModifiedAt < $this->objectCreatedAt) {
            throw new \InvalidArgumentException('Object modification timestamp cannot precede creation timestamp.');
        }

        $this->objectModifiedAt = $effectiveModifiedAt;
        $this->objectModifiedBy = $modifiedBy;
    }
}
