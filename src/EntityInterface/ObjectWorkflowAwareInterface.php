<?php

declare(strict_types=1);

namespace App\Objecting\EntityInterface;

interface ObjectWorkflowAwareInterface
{
    public function getObjectWorkflowState(): string;

    /** @param array<string, mixed> $objectWorkflowContext */
    public function setObjectWorkflowState(string $objectWorkflowState, array $objectWorkflowContext = []): void;

    /** @return array<string, mixed> */
    public function getObjectWorkflowContext(): array;
}
