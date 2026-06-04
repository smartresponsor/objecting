<?php

declare(strict_types=1);

namespace App\Objecting\ResolverInterface\FieldPack;

use App\Objecting\Contract\ObjectFieldPackConsumerContract;
use App\Objecting\Contract\ObjectResolvedFieldPackConsumerContract;

interface ObjectFieldPackConsumerContractResolverInterface
{
    public function resolve(ObjectFieldPackConsumerContract $contract): ObjectResolvedFieldPackConsumerContract;
}
