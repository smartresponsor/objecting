<?php

declare(strict_types=1);

namespace App\Objecting\ServiceInterface\FieldPack;

use App\Objecting\ValueObject\ObjectFieldPackConsumerContract;
use App\Objecting\ValueObject\ObjectResolvedFieldPackConsumerContract;

interface ObjectFieldPackConsumerContractResolverInterface
{
    public function resolve(ObjectFieldPackConsumerContract $contract): ObjectResolvedFieldPackConsumerContract;
}
