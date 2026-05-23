<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectWorkflowEmbeddable
{
    #[ORM\Column(name: 'object_workflow_state', type: 'string', length: 120)]
    private string $objectWorkflowState = 'draft';

    /** @var array<string, mixed> */
    #[ORM\Column(name: 'object_workflow_context', type: 'json')]
    private array $objectWorkflowContext = [];

    public function getObjectWorkflowState(): string
    {
        return $this->objectWorkflowState;
    }

    /** @param array<string, mixed> $objectWorkflowContext */
    public function setObjectWorkflowState(string $objectWorkflowState, array $objectWorkflowContext = []): void
    {
        $this->objectWorkflowState = $objectWorkflowState;
        $this->objectWorkflowContext = $objectWorkflowContext;
    }

    /** @return array<string, mixed> */
    public function getObjectWorkflowContext(): array
    {
        return $this->objectWorkflowContext;
    }
}
