<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Registry\FieldPack\ObjectFieldPackProfileRegistry;
use App\Objecting\Registry\FieldPack\ObjectFieldPackRegistry;
use App\Objecting\ValueObject\ObjectFieldPackName;
use App\Objecting\ValueObject\ObjectFieldPackProfileName;
use PHPUnit\Framework\TestCase;

final class ObjectFieldPackRegistryTest extends TestCase
{
    public function testRegistryContainsEveryCanonicalFieldPackName(): void
    {
        $registry = new ObjectFieldPackRegistry();

        foreach (ObjectFieldPackName::all() as $fieldPackName) {
            self::assertTrue($registry->has($fieldPackName), $fieldPackName);
            self::assertSame($fieldPackName, $registry->get($fieldPackName)->nameEntity());
        }
    }

    public function testProfileRegistryContainsEveryCanonicalProfileName(): void
    {
        $registry = new ObjectFieldPackProfileRegistry();

        foreach (ObjectFieldPackProfileName::all() as $profileName) {
            self::assertTrue($registry->has($profileName), $profileName);
            self::assertSame($profileName, $registry->get($profileName)->nameEntity());
        }
    }

    public function testBaselineProfileContainsTitleFieldPack(): void
    {
        $profile = (new ObjectFieldPackProfileRegistry())->get(ObjectFieldPackProfileName::BASELINE);

        self::assertTrue($profile->contains(ObjectFieldPackName::IDENTITY));
        self::assertTrue($profile->contains(ObjectFieldPackName::AUDIT));
        self::assertTrue($profile->contains(ObjectFieldPackName::TITLE));
    }
}
