<?php

declare(strict_types=1);

namespace App\Objecting\ReporterInterface\FieldPack;

use App\Objecting\Contract\ObjectBackendImportContract;
use App\Objecting\Report\ObjectBackendImportReport;

interface ObjectBackendImportContractReporterInterface
{
    public function report(ObjectBackendImportContract $contract): ObjectBackendImportReport;
}
