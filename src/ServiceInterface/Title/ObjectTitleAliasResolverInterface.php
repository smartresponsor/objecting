<?php

declare(strict_types=1);

namespace App\Objecting\ServiceInterface\Title;

use App\Objecting\ValueObject\ObjectTitleAliasMap;

interface ObjectTitleAliasResolverInterface
{
    public function aliasFor(string $canonicalField, ObjectTitleAliasMap $aliasMap): string;
}
