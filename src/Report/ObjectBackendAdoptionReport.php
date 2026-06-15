<?php

declare(strict_types=1);

namespace App\Objecting\Report;

use App\Objecting\Manifest\ObjectBackendAdoptionManifest;

final readonly class ObjectBackendAdoptionReport
{
    public const STATUS_READY = 'ready';
    public const STATUS_BLOCKED = 'blocked';

    /**
     * @param list<string> $checks
     * @param list<string> $blockingReasons
     */
    public function __construct(
        private ObjectBackendAdoptionManifest $manifest,
        private ObjectBackendMigrationReadinessReport $migrationReadinessReport,
        private array $checks,
        private array $blockingReasons,
    ) {
        foreach ($this->checks as $check) {
            if ('' === $check) {
                throw new \InvalidArgumentException('Objecting backend adoption check cannot be empty.');
            }
        }

        foreach ($this->blockingReasons as $blockingReason) {
            if ('' === $blockingReason) {
                throw new \InvalidArgumentException('Objecting backend adoption blocking reason cannot be empty.');
            }
        }
    }

    public function manifest(): ObjectBackendAdoptionManifest
    {
        return $this->manifest;
    }

    public function migrationReadinessReport(): ObjectBackendMigrationReadinessReport
    {
        return $this->migrationReadinessReport;
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
        return [] === $this->blockingReasons && $this->migrationReadinessReport->isReady()
            ? self::STATUS_READY
            : self::STATUS_BLOCKED;
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
            'migration_readiness' => $this->migrationReadinessReport->toArray(),
            'checks' => $this->checks,
            'blocking_reasons' => $this->blockingReasons,
        ];
    }
}
