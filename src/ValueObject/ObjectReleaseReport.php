<?php

declare(strict_types=1);

namespace App\Objecting\ValueObject;

final readonly class ObjectReleaseReport
{
    /**
     * @param list<string> $checks
     * @param list<string> $blockingReasons
     */
    public function __construct(
        private ObjectReleaseManifest $manifest,
        private array $checks,
        private array $blockingReasons,
    ) {
    }

    public function manifest(): ObjectReleaseManifest
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

    public function isReady(): bool
    {
        return [] === $this->blockingReasons;
    }

    public function status(): string
    {
        return $this->isReady() ? 'ready' : 'blocked';
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'manifest' => $this->manifest->toArray(),
            'checks' => $this->checks,
            'blocking_reasons' => $this->blockingReasons,
            'status' => $this->status(),
        ];
    }
}
