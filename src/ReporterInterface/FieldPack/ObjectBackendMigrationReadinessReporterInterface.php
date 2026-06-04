<?php

declare(strict_types=1);

namespace App\Objecting\ReporterInterface\FieldPack;

use App\Objecting\Contract\ObjectFieldPackConsumerContract;
use App\Objecting\Report\ObjectBackendMigrationReadinessReport;

interface ObjectBackendMigrationReadinessReporterInterface
{
    public function report(ObjectFieldPackConsumerContract $contract): ObjectBackendMigrationReadinessReport;
}
