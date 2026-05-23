<?php

declare(strict_types=1);

namespace App\Objecting\ServiceInterface\Doctrine;

use App\Objecting\ValueObject\ObjectDoctrineMappingContract;
use App\Objecting\ValueObject\ObjectDoctrineMappingReport;

interface ObjectDoctrineMappingContractReporterInterface
{
    public function report(ObjectDoctrineMappingContract $contract): ObjectDoctrineMappingReport;
}
