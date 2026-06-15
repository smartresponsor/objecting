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

    public function getObjectCreatedAt(): \DateTimeImmutable
    {
        return $this->objectAuditEmbeddable()->getObjectCreatedAt();
    }

    public function getObjectModifiedAt(): ?\DateTimeImmutable
    {
        return $this->objectAuditEmbeddable()->getObjectModifiedAt();
    }

    public function getObjectCreatedBy(): ?string
    {
        return $this->objectAuditEmbeddable()->getObjectCreatedBy();
    }

    public function getObjectModifiedBy(): ?string
    {
        return $this->objectAuditEmbeddable()->getObjectModifiedBy();
    }

    public function touchModified(?\DateTimeImmutable $modifiedAt = null, ?string $modifiedBy = null): void
    {
        $this->objectAuditEmbeddable()->touchModified($modifiedAt, $modifiedBy);
    }
}
