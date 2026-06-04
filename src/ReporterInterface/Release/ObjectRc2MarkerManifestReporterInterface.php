<?php

declare(strict_types=1);

namespace App\Objecting\ReporterInterface\Release;

use App\Objecting\Manifest\ObjectRc2MarkerManifest;
use App\Objecting\Report\ObjectRc2MarkerReport;

interface ObjectRc2MarkerManifestReporterInterface
{
    public function report(ObjectRc2MarkerManifest $manifest): ObjectRc2MarkerReport;
}
