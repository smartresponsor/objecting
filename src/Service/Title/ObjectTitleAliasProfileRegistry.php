<?php

declare(strict_types=1);

namespace App\Objecting\Service\Title;

use App\Objecting\ServiceInterface\Title\ObjectTitleAliasProfileRegistryInterface;
use App\Objecting\ValueObject\ObjectTitleAliasMap;
use App\Objecting\ValueObject\ObjectTitleAliasProfile;
use App\Objecting\ValueObject\ObjectTitleAliasProfileName;

final class ObjectTitleAliasProfileRegistry implements ObjectTitleAliasProfileRegistryInterface
{
    /** @var array<string, ObjectTitleAliasProfile>|null */
    private ?array $profiles = null;

    /** @return array<string, ObjectTitleAliasProfile> */
    public function all(): array
    {
        return $this->profiles ??= $this->build();
    }

    public function get(string $nameEntity): ObjectTitleAliasProfile
    {
        $all = $this->all();

        if (!isset($all[$nameEntity])) {
            throw new \InvalidArgumentException(sprintf('Unknown Objecting title-alias profile "%s".', $nameEntity));
        }

        return $all[$nameEntity];
    }

    public function has(string $nameEntity): bool
    {
        return isset($this->all()[$nameEntity]);
    }

    /** @return array<string, ObjectTitleAliasProfile> */
    private function build(): array
    {
        return [
            ObjectTitleAliasProfileName::CANONICAL => new ObjectTitleAliasProfile(ObjectTitleAliasProfileName::CANONICAL, new ObjectTitleAliasMap()),
            ObjectTitleAliasProfileName::PERSON => new ObjectTitleAliasProfile(ObjectTitleAliasProfileName::PERSON, new ObjectTitleAliasMap([
                ObjectTitleAliasMap::FIRST_TITLE => 'firstName',
                ObjectTitleAliasMap::MIDDLE_TITLE => 'middleName',
                ObjectTitleAliasMap::LAST_TITLE => 'lastName',
            ])),
            ObjectTitleAliasProfileName::CONTENT => new ObjectTitleAliasProfile(ObjectTitleAliasProfileName::CONTENT, new ObjectTitleAliasMap([
                ObjectTitleAliasMap::FIRST_TITLE => ObjectTitleAliasMap::ALIAS_TITLE,
                ObjectTitleAliasMap::MIDDLE_TITLE => ObjectTitleAliasMap::ALIAS_SHORT_DESCRIPTION,
                ObjectTitleAliasMap::LAST_TITLE => ObjectTitleAliasMap::ALIAS_DESCRIPTION,
            ])),
            ObjectTitleAliasProfileName::PRODUCT => new ObjectTitleAliasProfile(ObjectTitleAliasProfileName::PRODUCT, new ObjectTitleAliasMap([
                ObjectTitleAliasMap::FIRST_TITLE => ObjectTitleAliasMap::ALIAS_NAME,
                ObjectTitleAliasMap::MIDDLE_TITLE => ObjectTitleAliasMap::ALIAS_SUBTITLE,
                ObjectTitleAliasMap::LAST_TITLE => ObjectTitleAliasMap::ALIAS_DESCRIPTION,
            ])),
            ObjectTitleAliasProfileName::ADDRESS => new ObjectTitleAliasProfile(ObjectTitleAliasProfileName::ADDRESS, new ObjectTitleAliasMap([
                ObjectTitleAliasMap::FIRST_TITLE => 'addressLine1',
                ObjectTitleAliasMap::MIDDLE_TITLE => 'addressLine2',
                ObjectTitleAliasMap::LAST_TITLE => 'displayLabel',
            ])),
            ObjectTitleAliasProfileName::ORGANIZATION => new ObjectTitleAliasProfile(ObjectTitleAliasProfileName::ORGANIZATION, new ObjectTitleAliasMap([
                ObjectTitleAliasMap::FIRST_TITLE => 'legalName',
                ObjectTitleAliasMap::MIDDLE_TITLE => 'tradeName',
                ObjectTitleAliasMap::LAST_TITLE => ObjectTitleAliasMap::ALIAS_DESCRIPTION,
            ])),
            ObjectTitleAliasProfileName::LABEL => new ObjectTitleAliasProfile(ObjectTitleAliasProfileName::LABEL, new ObjectTitleAliasMap([
                ObjectTitleAliasMap::FIRST_TITLE => ObjectTitleAliasMap::ALIAS_LABEL,
                ObjectTitleAliasMap::MIDDLE_TITLE => ObjectTitleAliasMap::ALIAS_SUMMARY,
                ObjectTitleAliasMap::LAST_TITLE => ObjectTitleAliasMap::ALIAS_DESCRIPTION,
            ])),
            ObjectTitleAliasProfileName::DISPLAY => new ObjectTitleAliasProfile(ObjectTitleAliasProfileName::DISPLAY, new ObjectTitleAliasMap([
                ObjectTitleAliasMap::FIRST_TITLE => ObjectTitleAliasMap::ALIAS_DISPLAY_NAME,
                ObjectTitleAliasMap::MIDDLE_TITLE => ObjectTitleAliasMap::ALIAS_SHORT_DESCRIPTION,
                ObjectTitleAliasMap::LAST_TITLE => ObjectTitleAliasMap::ALIAS_DESCRIPTION,
            ])),
        ];
    }
}
