<?php

declare(strict_types=1);

namespace App\Objecting\ReporterInterface\FieldPack;

use App\Objecting\Contract\ObjectBackendCloneCleanupContract;
use App\Objecting\Report\ObjectBackendCloneCleanupReport;

interface ObjectBackendCloneCleanupContractReporterInterface
{
    public function report(ObjectBackendCloneCleanupContract $contract): ObjectBackendCloneCleanupReport;
}
