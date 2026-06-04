<?php

declare(strict_types=1);

namespace App\Objecting\ReporterInterface\Release;

use App\Objecting\Manifest\ObjectReleaseManifest;
use App\Objecting\Report\ObjectReleaseReport;

interface ObjectReleaseManifestReporterInterface
{
    public function report(ObjectReleaseManifest $manifest): ObjectReleaseReport;
}
