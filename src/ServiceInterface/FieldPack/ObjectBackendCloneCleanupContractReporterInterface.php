<?php

declare(strict_types=1);

namespace App\Objecting\ServiceInterface\FieldPack;

use App\Objecting\ValueObject\ObjectBackendCloneCleanupContract;
use App\Objecting\ValueObject\ObjectBackendCloneCleanupReport;

interface ObjectBackendCloneCleanupContractReporterInterface
{
    public function report(ObjectBackendCloneCleanupContract $contract): ObjectBackendCloneCleanupReport;
}
