<?php

declare(strict_types=1);

namespace App\Objecting\ReporterInterface\Release;

use App\Objecting\Manifest\ObjectRcStabilizationManifest;
use App\Objecting\Report\ObjectRcStabilizationReport;

interface ObjectRcStabilizationManifestReporterInterface
{
    public function report(ObjectRcStabilizationManifest $manifest): ObjectRcStabilizationReport;
}
