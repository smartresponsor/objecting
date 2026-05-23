<?php

declare(strict_types=1);

namespace App\Objecting\ValueObject;

final readonly class ObjectSiblingPilotMigrationHandoffReport
{
    public const STATUS_READY = 'ready';
    public const STATUS_BLOCKED = 'blocked';

    /**
     * @param list<string> $checks
     * @param list<string> $blockingReasons
     */
    public function __construct(
        private ObjectSiblingPilotMigrationHandoffManifest $manifest,
        private array $checks,
        private array $blockingReasons,
    ) {
        foreach ($this->checks as $check) {
            if ('' === $check) {
                throw new \InvalidArgumentException('Objecting sibling pilot migration handoff check cannot be empty.');
            }
        }
        foreach ($this->blockingReasons as $blockingReason) {
            if ('' === $blockingReason) {
                throw new \InvalidArgumentException('Objecting sibling pilot migration handoff blocking reason cannot be empty.');
            }
        }
    }

    public function manifest(): ObjectSiblingPilotMigrationHandoffManifest
    {
        return $this->manifest;
    }

    /** @return list<string> */
    public function checks(): array
    {
        return $this->checks;
    }

    /** @return list<string> */
    public function blockingReasons(): array
    {
        return $this->blockingReasons;
    }

    public function status(): string
    {
        return [] === $this->blockingReasons ? self::STATUS_READY : self::STATUS_BLOCKED;
    }

    public function isReady(): bool
    {
        return self::STATUS_READY === $this->status();
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'status' => $this->status(),
            'manifest' => $this->manifest->toArray(),
            'checks' => $this->checks,
            'blocking_reasons' => $this->blockingReasons,
        ];
    }
}
