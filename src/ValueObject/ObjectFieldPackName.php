<?php

declare(strict_types=1);

namespace App\Objecting\ValueObject;

final class ObjectFieldPackName
{
    public const IDENTITY = 'object_identity';
    public const AUDIT = 'object_audit';
    public const TITLE = 'object_title';
    public const PUBLICATION = 'object_publication';
    public const SOFT_DELETE = 'object_soft_delete';
    public const VERSION = 'object_version';
    public const LOCALE = 'object_locale';
    public const TOKEN = 'object_token';
    public const RESTRICTION = 'object_restriction';
    public const LOCK = 'object_lock';
    public const WORKFLOW = 'object_workflow';
    public const CONFIG = 'object_config';
    public const CODE = 'object_code';
    public const STATE = 'object_state';
    public const SOURCE = 'object_source';
    public const FINGERPRINT = 'object_fingerprint';

    /** @return list<string> */
    public static function all(): array
    {
        return [
            self::IDENTITY,
            self::AUDIT,
            self::TITLE,
            self::PUBLICATION,
            self::SOFT_DELETE,
            self::VERSION,
            self::LOCALE,
            self::TOKEN,
            self::RESTRICTION,
            self::LOCK,
            self::WORKFLOW,
            self::CONFIG,
            self::CODE,
            self::STATE,
            self::SOURCE,
            self::FINGERPRINT,
        ];
    }

    public static function isKnown(string $name): bool
    {
        return in_array($name, self::all(), true);
    }
}
