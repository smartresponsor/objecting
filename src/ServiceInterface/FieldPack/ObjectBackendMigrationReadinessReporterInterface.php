<?php

declare(strict_types=1);

namespace App\Objecting\ServiceInterface\FieldPack;

use App\Objecting\ValueObject\ObjectBackendMigrationReadinessReport;
use App\Objecting\ValueObject\ObjectFieldPackConsumerContract;

interface ObjectBackendMigrationReadinessReporterInterface
{
    public function report(ObjectFieldPackConsumerContract $contract): ObjectBackendMigrationReadinessReport;
}
