<?php

declare(strict_types=1);

namespace App\Objecting\ValueObject;

final readonly class ObjectTitleAliasMap
{
    public const FIRST_TITLE = 'firstTitle';
    public const MIDDLE_TITLE = 'middleTitle';
    public const LAST_TITLE = 'lastTitle';

    public const ALIAS_NAME = 'name';
    public const ALIAS_TITLE = 'title';
    public const ALIAS_DESCRIPTION = 'description';
    public const ALIAS_SHORT_DESCRIPTION = 'shortDescription';
    public const ALIAS_LABEL = 'label';
    public const ALIAS_DISPLAY_NAME = 'displayName';
    public const ALIAS_SUMMARY = 'summary';
    public const ALIAS_SUBTITLE = 'subtitle';

    /** @var array<string, string> */
    private array $aliases;

    /** @param array<string, string> $aliases */
    public function __construct(array $aliases = [])
    {
        foreach ($aliases as $canonicalField => $alias) {
            if (!in_array($canonicalField, self::canonicalFields(), true)) {
                throw new \InvalidArgumentException(sprintf('Unknown Objecting title canonical field "%s".', $canonicalField));
            }
            if ('' === $alias) {
                throw new \InvalidArgumentException(sprintf('Objecting title alias for "%s" cannot be empty.', $canonicalField));
            }
        }

        $mergedAliases = array_replace($this->canonicalAliases(), $aliases);
        $duplicateAliases = array_keys(array_filter(array_count_values($mergedAliases), static fn (int $count): bool => $count > 1));
        if ([] !== $duplicateAliases) {
            throw new \InvalidArgumentException(sprintf('Objecting title aliases must be unique. Duplicate aliases: %s.', implode(', ', $duplicateAliases)));
        }

        $this->aliases = $mergedAliases;
    }

    public function aliasFor(string $canonicalField): string
    {
        return $this->aliases[$canonicalField] ?? $canonicalField;
    }

    public function hasAlias(string $alias): bool
    {
        return in_array($alias, $this->aliases, true);
    }

    public function canonicalFieldForAlias(string $alias): ?string
    {
        foreach ($this->aliases as $canonicalField => $mappedAlias) {
            if ($mappedAlias === $alias) {
                return $canonicalField;
            }
        }

        return null;
    }

    /** @return array<string, string> */
    public function all(): array
    {
        return $this->aliases;
    }

    /** @return list<string> */
    public static function canonicalFields(): array
    {
        return [self::FIRST_TITLE, self::MIDDLE_TITLE, self::LAST_TITLE];
    }

    /** @return list<string> */
    public static function commonBusinessAliases(): array
    {
        return [
            self::ALIAS_NAME,
            self::ALIAS_TITLE,
            self::ALIAS_DESCRIPTION,
            self::ALIAS_SHORT_DESCRIPTION,
            self::ALIAS_LABEL,
            self::ALIAS_DISPLAY_NAME,
            self::ALIAS_SUMMARY,
            self::ALIAS_SUBTITLE,
        ];
    }

    /** @return array<string, string> */
    public static function canonicalAliases(): array
    {
        return [
            self::FIRST_TITLE => self::FIRST_TITLE,
            self::MIDDLE_TITLE => self::MIDDLE_TITLE,
            self::LAST_TITLE => self::LAST_TITLE,
        ];
    }
}
