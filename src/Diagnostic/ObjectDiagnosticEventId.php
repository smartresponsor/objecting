<?php

declare(strict_types=1);

namespace App\Objecting\Diagnostic;

final class ObjectDiagnosticEventId
{
    public const MANIFEST_INVALID = 'objecting.manifest.invalid';
    public const PROFILE_UNKNOWN = 'objecting.profile.unknown';
    public const FIELD_PACK_UNKNOWN = 'objecting.field_pack.unknown';
    public const LIFECYCLE_INVARIANT_VIOLATION = 'objecting.lifecycle.invariant_violation';
    public const SCHEMA_MISMATCH = 'objecting.schema.mismatch';
    public const TITLE_ALIAS_CONFLICT = 'objecting.title_alias.conflict';
    public const DUPLICATE_LOCAL_SYSTEM_FIELD = 'objecting.consumer.duplicate_local_system_field';
    public const DEPRECATED_PUBLIC_API = 'objecting.public_api.deprecated';

    /** @return list<string> */
    public static function all(): array
    {
        return [
            self::MANIFEST_INVALID,
            self::PROFILE_UNKNOWN,
            self::FIELD_PACK_UNKNOWN,
            self::LIFECYCLE_INVARIANT_VIOLATION,
            self::SCHEMA_MISMATCH,
            self::TITLE_ALIAS_CONFLICT,
            self::DUPLICATE_LOCAL_SYSTEM_FIELD,
            self::DEPRECATED_PUBLIC_API,
        ];
    }

    public static function isKnown(string $eventId): bool
    {
        return in_array($eventId, self::all(), true);
    }
}
