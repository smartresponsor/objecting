<?php

declare(strict_types=1);

namespace App\Objecting\Report;

final readonly class ObjectDiagnosticReport implements \JsonSerializable
{
    /**
     * @param array<string, scalar|array<array-key, scalar|null>|null> $context
     */
    public function __construct(
        private string $eventId,
        private string $message,
        private array $context = [],
    ) {
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function message(): string
    {
        return $this->message;
    }

    /** @return array<string, scalar|array<array-key, scalar|null>|null> */
    public function context(): array
    {
        return $this->context;
    }

    /** @return array{event_id: string, message: string, context: array<string, scalar|array<array-key, scalar|null>|null>} */
    public function jsonSerialize(): array
    {
        return [
            'event_id' => $this->eventId,
            'message' => $this->message,
            'context' => $this->context,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);
    }
}
