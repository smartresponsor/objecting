<?php

declare(strict_types=1);

namespace App\Objecting\ServiceInterface\FieldPack;

use App\Objecting\ValueObject\ObjectBackendImportContract;
use App\Objecting\ValueObject\ObjectBackendImportReport;

interface ObjectBackendImportContractReporterInterface
{
    public function report(ObjectBackendImportContract $contract): ObjectBackendImportReport;
}
