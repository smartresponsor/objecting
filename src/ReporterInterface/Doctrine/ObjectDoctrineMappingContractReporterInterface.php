<?php

declare(strict_types=1);

namespace App\Objecting\ReporterInterface\Doctrine;

use App\Objecting\Contract\ObjectDoctrineMappingContract;
use App\Objecting\Report\ObjectDoctrineMappingReport;

interface ObjectDoctrineMappingContractReporterInterface
{
    public function report(ObjectDoctrineMappingContract $contract): ObjectDoctrineMappingReport;
}
