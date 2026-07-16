<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use App\Objecting\Embeddable\ObjectAuditEmbeddable;
use Doctrine\ORM\Mapping as ORM;

trait ObjectAuditEmbeddableTrait
{
    #[ORM\Embedded(class: ObjectAuditEmbeddable::class, columnPrefix: false)]
    private ObjectAuditEmbeddable $objectAudit;

    protected function initializeObjectAudit(?\DateTimeImmutable $createdAt = null, ?string $createdBy = null): void
    {
        $this->objectAudit = new ObjectAuditEmbeddable($createdAt, $createdBy);
    }

    private function objectAuditEmbeddable(): ObjectAuditEmbeddable
    {
        if (!isset($this->objectAudit)) {
            $this->objectAudit = new ObjectAuditEmbeddable();
        }

        return $this->objectAudit;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->objectAuditEmbeddable()->getCreatedAt();
    }

    public function getModifiedAt(): ?\DateTimeImmutable
    {
        return $this->objectAuditEmbeddable()->getModifiedAt();
    }

    public function getCreatedBy(): ?string
    {
        return $this->objectAuditEmbeddable()->getCreatedBy();
    }

    public function getModifiedBy(): ?string
    {
        return $this->objectAuditEmbeddable()->getModifiedBy();
    }

    public function touchModified(?\DateTimeImmutable $modifiedAt = null, ?string $modifiedBy = null): void
    {
        $this->objectAuditEmbeddable()->touchModified($modifiedAt, $modifiedBy);
    }
}
