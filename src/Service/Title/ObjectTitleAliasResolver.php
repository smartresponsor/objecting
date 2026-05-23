<?php

declare(strict_types=1);

namespace App\Objecting\Service\Title;

use App\Objecting\ServiceInterface\Title\ObjectTitleAliasResolverInterface;
use App\Objecting\ValueObject\ObjectTitleAliasMap;

final class ObjectTitleAliasResolver implements ObjectTitleAliasResolverInterface
{
    public function aliasFor(string $canonicalField, ObjectTitleAliasMap $aliasMap): string
    {
        return $aliasMap->aliasFor($canonicalField);
    }
}
