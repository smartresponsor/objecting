<?php

declare(strict_types=1);

namespace App\Objecting\ServiceInterface\Exposing;

use App\Objecting\ValueObject\ObjectExposingBridgeContract;
use App\Objecting\ValueObject\ObjectExposingBridgeReport;

interface ObjectExposingBridgeContractReporterInterface
{
    public function report(ObjectExposingBridgeContract $contract): ObjectExposingBridgeReport;
}
