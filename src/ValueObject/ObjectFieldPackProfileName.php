<?php

declare(strict_types=1);

namespace App\Objecting\ValueObject;

final class ObjectFieldPackProfileName
{
    public const BASELINE = 'object_baseline';
    public const CONTENT = 'object_content';
    public const LIFECYCLE = 'object_lifecycle';
    public const SECURITY = 'object_security';
    public const LOCALIZED = 'object_localized';
    public const SYSTEMIC = 'object_systemic';

    /** @return list<string> */
    public static function all(): array
    {
        return [
            self::BASELINE,
            self::CONTENT,
            self::LIFECYCLE,
            self::SECURITY,
            self::LOCALIZED,
            self::SYSTEMIC,
        ];
    }

    public static function isKnown(string $nameEntity): bool
    {
        return in_array($nameEntity, self::all(), true);
    }
}
