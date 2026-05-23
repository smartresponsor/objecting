<?php

declare(strict_types=1);

namespace App\Objecting\ServiceInterface\FieldPack;

use App\Objecting\ValueObject\ObjectBackendAdoptionPacketManifest;
use App\Objecting\ValueObject\ObjectBackendAdoptionPacketReport;

interface ObjectBackendAdoptionPacketManifestReporterInterface
{
    public function report(ObjectBackendAdoptionPacketManifest $manifest): ObjectBackendAdoptionPacketReport;
}
