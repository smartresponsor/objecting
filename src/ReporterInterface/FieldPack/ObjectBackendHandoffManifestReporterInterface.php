<?php

declare(strict_types=1);

namespace App\Objecting\ReporterInterface\FieldPack;

use App\Objecting\Manifest\ObjectBackendHandoffManifest;
use App\Objecting\Report\ObjectBackendHandoffReport;

interface ObjectBackendHandoffManifestReporterInterface
{
    public function report(ObjectBackendHandoffManifest $manifest): ObjectBackendHandoffReport;
}
