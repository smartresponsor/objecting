<?php

declare(strict_types=1);

namespace App\Objecting\ReporterInterface\Exposing;

use App\Objecting\Contract\ObjectExposingBridgeContract;
use App\Objecting\Report\ObjectExposingBridgeReport;

interface ObjectExposingBridgeContractReporterInterface
{
    public function report(ObjectExposingBridgeContract $contract): ObjectExposingBridgeReport;
}
