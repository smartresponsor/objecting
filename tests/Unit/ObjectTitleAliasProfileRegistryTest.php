<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Registry\Title\ObjectTitleAliasProfileRegistry;
use App\Objecting\ValueObject\ObjectTitleAliasProfileName;
use PHPUnit\Framework\TestCase;

final class ObjectTitleAliasProfileRegistryTest extends TestCase
{
    public function testRegistryContainsEveryCanonicalTitleAliasProfile(): void
    {
        $registry = new ObjectTitleAliasProfileRegistry();

        foreach (ObjectTitleAliasProfileName::all() as $profileName) {
            self::assertTrue($registry->has($profileName), $profileName);
            self::assertSame($profileName, $registry->get($profileName)->nameEntity());
        }
    }

    public function testContentProfileMapsCanonicalTitlesToContentNames(): void
    {
        $profile = (new ObjectTitleAliasProfileRegistry())->get(ObjectTitleAliasProfileName::CONTENT);

        self::assertSame('title', $profile->aliasFor('firstTitle'));
        self::assertSame('shortDescription', $profile->aliasFor('middleTitle'));
        self::assertSame('description', $profile->aliasFor('lastTitle'));
    }

    public function testPersonProfileMapsCanonicalTitlesToNameParts(): void
    {
        $profile = (new ObjectTitleAliasProfileRegistry())->get(ObjectTitleAliasProfileName::PERSON);

        self::assertSame('firstName', $profile->aliasFor('firstTitle'));
        self::assertSame('middleName', $profile->aliasFor('middleTitle'));
        self::assertSame('lastName', $profile->aliasFor('lastTitle'));
    }
}
