<?php

declare(strict_types=1);

namespace App\Objecting\ReporterInterface\Schema;

use App\Objecting\Contract\ObjectSchemaMirrorContract;
use App\Objecting\Report\ObjectSchemaMirrorReport;

interface ObjectSchemaMirrorContractReporterInterface
{
    public function report(ObjectSchemaMirrorContract $contract): ObjectSchemaMirrorReport;
}
