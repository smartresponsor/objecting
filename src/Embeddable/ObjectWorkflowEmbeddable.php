<?php

declare(strict_types=1);

namespace App\Objecting\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class ObjectWorkflowEmbeddable
{
    #[ORM\Column(name: 'workflow_state', type: 'string', length: 120)]
    private string $workflowState = 'draft';

    /** @var array<string, mixed> */
    #[ORM\Column(name: 'workflow_context', type: 'json')]
    private array $workflowContext = [];

    public function getObjectWorkflowState(): string
    {
        return $this->workflowState;
    }

    /** @param array<string, mixed> $objectWorkflowContext */
    public function setObjectWorkflowState(string $objectWorkflowState, array $objectWorkflowContext = []): void
    {
        $this->workflowState = $objectWorkflowState;
        $this->workflowContext = $objectWorkflowContext;
    }

    /** @return array<string, mixed> */
    public function getObjectWorkflowContext(): array
    {
        return $this->workflowContext;
    }
}
