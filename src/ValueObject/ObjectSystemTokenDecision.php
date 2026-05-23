<?php

declare(strict_types=1);

namespace App\Objecting\ValueObject;

final class ObjectSystemTokenDecision
{
    public const OBJECT_TITLE_ALIAS = 'object_title_alias';
    public const BACKEND_OWNED = 'backend_owned';
    public const DEFERRED = 'deferred';

    public const TOKEN_ID = 'id';
    public const TOKEN_NAME = 'name';
    public const TOKEN_TITLE = 'title';
    public const TOKEN_DESCRIPTION = 'description';
    public const TOKEN_SHORT_DESCRIPTION = 'shortDescription';
    public const TOKEN_LABEL = 'label';
    public const TOKEN_DISPLAY_NAME = 'displayName';
    public const TOKEN_PRIORITY = 'priority';
    public const TOKEN_VISIBILITY = 'visibility';

    /** @return array<string, string> */
    public static function canonicalDecisions(): array
    {
        return [
            self::TOKEN_ID => self::BACKEND_OWNED,
            self::TOKEN_NAME => self::OBJECT_TITLE_ALIAS,
            self::TOKEN_TITLE => self::OBJECT_TITLE_ALIAS,
            self::TOKEN_DESCRIPTION => self::OBJECT_TITLE_ALIAS,
            self::TOKEN_SHORT_DESCRIPTION => self::OBJECT_TITLE_ALIAS,
            self::TOKEN_LABEL => self::OBJECT_TITLE_ALIAS,
            self::TOKEN_DISPLAY_NAME => self::OBJECT_TITLE_ALIAS,
            self::TOKEN_PRIORITY => self::DEFERRED,
            self::TOKEN_VISIBILITY => self::DEFERRED,
        ];
    }

    /** @return list<string> */
    public static function titleAliasTokens(): array
    {
        return array_keys(array_filter(self::canonicalDecisions(), static fn (string $decision): bool => self::OBJECT_TITLE_ALIAS === $decision));
    }

    /** @return list<string> */
    public static function backendOwnedTokens(): array
    {
        return array_keys(array_filter(self::canonicalDecisions(), static fn (string $decision): bool => self::BACKEND_OWNED === $decision));
    }

    /** @return list<string> */
    public static function deferredTokens(): array
    {
        return array_keys(array_filter(self::canonicalDecisions(), static fn (string $decision): bool => self::DEFERRED === $decision));
    }
}
