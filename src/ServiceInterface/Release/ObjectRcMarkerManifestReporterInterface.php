<?php

declare(strict_types=1);

namespace App\Objecting\ServiceInterface\Release;

use App\Objecting\ValueObject\ObjectRcMarkerManifest;
use App\Objecting\ValueObject\ObjectRcMarkerReport;

interface ObjectRcMarkerManifestReporterInterface
{
    public function report(ObjectRcMarkerManifest $manifest): ObjectRcMarkerReport;
}
