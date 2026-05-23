<?php

declare(strict_types=1);

namespace App\Objecting\ServiceInterface\Release;

use App\Objecting\ValueObject\ObjectRc2MarkerManifest;
use App\Objecting\ValueObject\ObjectRc2MarkerReport;

interface ObjectRc2MarkerManifestReporterInterface
{
    public function report(ObjectRc2MarkerManifest $manifest): ObjectRc2MarkerReport;
}
