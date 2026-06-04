<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Contract\ObjectFieldPackConsumerContract;
use App\Objecting\Resolver\FieldPack\ObjectFieldPackConsumerContractResolver;
use App\Objecting\ValueObject\ObjectFieldPackName;
use App\Objecting\ValueObject\ObjectFieldPackProfileName;
use App\Objecting\ValueObject\ObjectTitleAliasProfileName;
use PHPUnit\Framework\TestCase;

final class ObjectFieldPackConsumerContractResolverTest extends TestCase
{
    public function testItExpandsAProfileIntoEffectiveFieldPacks(): void
    {
        $contract = new ObjectFieldPackConsumerContract(
            component: 'Paging',
            businessStem: 'Page',
            entityClass: 'App\\Paging\\Entity\\Page',
            fieldPacks: [],
            fieldPackProfile: ObjectFieldPackProfileName::CONTENT,
            titleAliasProfile: ObjectTitleAliasProfileName::CONTENT,
        );

        $resolved = (new ObjectFieldPackConsumerContractResolver())->resolve($contract);

        self::assertSame([], $resolved->explicitFieldPacks());
        self::assertTrue($resolved->usesFieldPack(ObjectFieldPackName::IDENTITY));
        self::assertTrue($resolved->usesFieldPack(ObjectFieldPackName::AUDIT));
        self::assertTrue($resolved->usesFieldPack(ObjectFieldPackName::TITLE));
        self::assertTrue($resolved->usesFieldPack(ObjectFieldPackName::PUBLICATION));
        self::assertTrue($resolved->usesFieldPack(ObjectFieldPackName::VERSION));
    }

    public function testItRejectsTitleAliasesWithoutTitleFieldPack(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $contract = new ObjectFieldPackConsumerContract(
            component: 'Messaging',
            businessStem: 'Message',
            entityClass: 'App\\Messaging\\Entity\\Message',
            fieldPacks: [ObjectFieldPackName::IDENTITY, ObjectFieldPackName::AUDIT],
            titleAliasProfile: ObjectTitleAliasProfileName::CONTENT,
        );

        (new ObjectFieldPackConsumerContractResolver())->resolve($contract);
    }

    public function testItRejectsEmptyEffectiveSelection(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $contract = new ObjectFieldPackConsumerContract(
            component: 'Emptying',
            businessStem: 'Empty',
            entityClass: 'App\\Emptying\\Entity\\EmptyObject',
            fieldPacks: [],
        );

        (new ObjectFieldPackConsumerContractResolver())->resolve($contract);
    }
}
