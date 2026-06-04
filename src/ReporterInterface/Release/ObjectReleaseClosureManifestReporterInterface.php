<?php

declare(strict_types=1);

namespace App\Objecting\ReporterInterface\Release;

use App\Objecting\Manifest\ObjectReleaseClosureManifest;
use App\Objecting\Report\ObjectReleaseClosureReport;

interface ObjectReleaseClosureManifestReporterInterface
{
    public function report(ObjectReleaseClosureManifest $manifest): ObjectReleaseClosureReport;
}
