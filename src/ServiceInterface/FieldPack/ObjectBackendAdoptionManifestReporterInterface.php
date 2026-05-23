<?php

declare(strict_types=1);

namespace App\Objecting\ServiceInterface\FieldPack;

use App\Objecting\ValueObject\ObjectBackendAdoptionManifest;
use App\Objecting\ValueObject\ObjectBackendAdoptionReport;

interface ObjectBackendAdoptionManifestReporterInterface
{
    public function report(ObjectBackendAdoptionManifest $manifest): ObjectBackendAdoptionReport;
}
