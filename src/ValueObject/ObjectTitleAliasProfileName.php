<?php

declare(strict_types=1);

namespace App\Objecting\ValueObject;

final class ObjectTitleAliasProfileName
{
    public const CANONICAL = 'object_title_canonical';
    public const PERSON = 'object_title_person';
    public const CONTENT = 'object_title_content';
    public const PRODUCT = 'object_title_product';
    public const ADDRESS = 'object_title_address';
    public const ORGANIZATION = 'object_title_organization';
    public const LABEL = 'object_title_label';
    public const DISPLAY = 'object_title_display';

    /** @return list<string> */
    public static function all(): array
    {
        return [
            self::CANONICAL,
            self::PERSON,
            self::CONTENT,
            self::PRODUCT,
            self::ADDRESS,
            self::ORGANIZATION,
            self::LABEL,
            self::DISPLAY,
        ];
    }

    public static function isKnown(string $nameEntity): bool
    {
        return in_array($nameEntity, self::all(), true);
    }
}
