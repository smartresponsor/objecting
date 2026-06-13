<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\ValueObject\ObjectTitleAliasMap;
use PHPUnit\Framework\TestCase;

final class ObjectTitleAliasMapTest extends TestCase
{
    public function testItKeepsCanonicalFieldsWhenAliasesAreNotProvided(): void
    {
        $map = new ObjectTitleAliasMap();

        self::assertSame('firstTitle', $map->aliasFor('firstTitle'));
        self::assertSame('middleTitle', $map->aliasFor('middleTitle'));
        self::assertSame('lastTitle', $map->aliasFor('lastTitle'));
    }

    public function testItResolvesBusinessAliases(): void
    {
        $map = new ObjectTitleAliasMap([
            'firstTitle' => 'title',
            'middleTitle' => 'shortDescription',
            'lastTitle' => 'description',
        ]);

        self::assertSame('title', $map->aliasFor('firstTitle'));
        self::assertSame('shortDescription', $map->aliasFor('middleTitle'));
        self::assertSame('description', $map->aliasFor('lastTitle'));
    }

    public function testItRejectsUnknownCanonicalField(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new ObjectTitleAliasMap(['displayName' => 'nameEntity']);
    }
}
