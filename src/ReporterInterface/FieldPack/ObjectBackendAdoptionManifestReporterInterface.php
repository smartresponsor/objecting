<?php

declare(strict_types=1);

namespace App\Objecting\ReporterInterface\FieldPack;

use App\Objecting\Manifest\ObjectBackendAdoptionManifest;
use App\Objecting\Report\ObjectBackendAdoptionReport;

interface ObjectBackendAdoptionManifestReporterInterface
{
    public function report(ObjectBackendAdoptionManifest $manifest): ObjectBackendAdoptionReport;
}
