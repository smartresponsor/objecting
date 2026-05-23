<?php

declare(strict_types=1);

namespace App\Objecting\ServiceInterface\Schema;

use App\Objecting\ValueObject\ObjectSchemaMirrorContract;
use App\Objecting\ValueObject\ObjectSchemaMirrorReport;

interface ObjectSchemaMirrorContractReporterInterface
{
    public function report(ObjectSchemaMirrorContract $contract): ObjectSchemaMirrorReport;
}
