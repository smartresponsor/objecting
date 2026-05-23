<?php

declare(strict_types=1);

namespace App\Objecting\ServiceInterface\Release;

use App\Objecting\ValueObject\ObjectRcStabilizationManifest;
use App\Objecting\ValueObject\ObjectRcStabilizationReport;

interface ObjectRcStabilizationManifestReporterInterface
{
    public function report(ObjectRcStabilizationManifest $manifest): ObjectRcStabilizationReport;
}
