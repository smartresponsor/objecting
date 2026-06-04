<?php

declare(strict_types=1);

namespace App\Objecting\ReporterInterface\FieldPack;

use App\Objecting\Manifest\ObjectBackendAdoptionPacketManifest;
use App\Objecting\Report\ObjectBackendAdoptionPacketReport;

interface ObjectBackendAdoptionPacketManifestReporterInterface
{
    public function report(ObjectBackendAdoptionPacketManifest $manifest): ObjectBackendAdoptionPacketReport;
}
