<?php

declare(strict_types=1);

namespace App\Objecting\EntityTrait\Embeddable;

use Doctrine\ORM\Mapping as ORM;

trait ObjectWorkflowEmbeddableTrait
{
    #[ORM\Embedded(class: \App\Objecting\Embeddable\ObjectWorkflowEmbeddable::class, columnPrefix: false)]
    private \App\Objecting\Embeddable\ObjectWorkflowEmbeddable $objectWorkflow;

    protected function initializeObjectWorkflow(): void
    {
        $this->objectWorkflow = new \App\Objecting\Embeddable\ObjectWorkflowEmbeddable();
    }

    private function objectWorkflowEmbeddable(): \App\Objecting\Embeddable\ObjectWorkflowEmbeddable
    {
        if (!isset($this->objectWorkflow)) {
            $this->objectWorkflow = new \App\Objecting\Embeddable\ObjectWorkflowEmbeddable();
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
