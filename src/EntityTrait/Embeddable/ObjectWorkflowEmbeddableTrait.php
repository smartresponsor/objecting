<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use App\Objecting\Embeddable\ObjectWorkflowEmbeddable;
use Doctrine\ORM\Mapping as ORM;

trait ObjectWorkflowEmbeddableTrait
{
    #[ORM\Embedded(class: ObjectWorkflowEmbeddable::class, columnPrefix: false)]
    private ObjectWorkflowEmbeddable $objectWorkflow;

    protected function initializeObjectWorkflow(): void
    {
        $this->objectWorkflow = new ObjectWorkflowEmbeddable();
    }

    private function objectWorkflowEmbeddable(): ObjectWorkflowEmbeddable
    {
        if (!isset($this->objectWorkflow)) {
            $this->objectWorkflow = new ObjectWorkflowEmbeddable();
        }

        return $this->objectWorkflow;
    }

    public function getObjectWorkflowState(): string
    {
        return $this->objectWorkflowEmbeddable()->getObjectWorkflowState();
    }

    /** @param array<string, mixed> $objectWorkflowContext */
    public function setObjectWorkflowState(string $objectWorkflowState, array $objectWorkflowContext = []): void
    {
        $this->objectWorkflowEmbeddable()->setObjectWorkflowState($objectWorkflowState, $objectWorkflowContext);
    }

    /** @return array<string, mixed> */
    public function getObjectWorkflowContext(): array
    {
        return $this->objectWorkflowEmbeddable()->getObjectWorkflowContext();
    }
}
