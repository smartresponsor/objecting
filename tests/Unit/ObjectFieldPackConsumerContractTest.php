<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Contract\ObjectFieldPackConsumerContract;
use App\Objecting\ValueObject\ObjectFieldPackName;
use App\Objecting\ValueObject\ObjectFieldPackProfileName;
use App\Objecting\ValueObject\ObjectTitleAliasProfileName;
use PHPUnit\Framework\TestCase;

final class ObjectFieldPackConsumerContractTest extends TestCase
{
    public function testItAcceptsKnownFieldPackAndAliasProfiles(): void
    {
        $contract = new ObjectFieldPackConsumerContract(
            component: 'Paging',
            businessStem: 'Page',
            entityClass: 'App\\Paging\\Entity\\Page',
            fieldPacks: [ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT, ObjectFieldPackName::TITLE],
            fieldPackProfile: ObjectFieldPackProfileName::BASELINE,
            titleAliasProfile: ObjectTitleAliasProfileName::CONTENT,
        );

        self::assertSame('Paging', $contract->component());
        self::assertTrue($contract->usesFieldPack(ObjectFieldPackName::TITLE));
        self::assertSame(ObjectTitleAliasProfileName::CONTENT, $contract->titleAliasProfile());
    }

    public function testItRejectsBothInlineTitleAliasMapAndAliasProfile(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new ObjectFieldPackConsumerContract(
            component: 'Paging',
            businessStem: 'Page',
            entityClass: 'App\\Paging\\Entity\\Page',
            fieldPacks: [ObjectFieldPackName::TITLE],
            titleAliasMap: new \App\Objecting\ValueObject\ObjectTitleAliasMap(),
            titleAliasProfile: ObjectTitleAliasProfileName::CONTENT,
        );
    }

    public function testItRejectsDuplicateFieldPacks(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new ObjectFieldPackConsumerContract(
            component: 'Paging',
            businessStem: 'Page',
            entityClass: 'App\\Paging\\Entity\\Page',
            fieldPacks: [ObjectFieldPackName::TITLE, ObjectFieldPackName::TITLE],
        );
    }
}
