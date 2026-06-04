<?php

declare(strict_types=1);

namespace App\Objecting\ReporterInterface\Release;

use App\Objecting\Manifest\ObjectRcMarkerManifest;
use App\Objecting\Report\ObjectRcMarkerReport;

interface ObjectRcMarkerManifestReporterInterface
{
    public function report(ObjectRcMarkerManifest $manifest): ObjectRcMarkerReport;
}
