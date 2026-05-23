<?php

declare(strict_types=1);

namespace App\Objecting\ServiceInterface\Release;

use App\Objecting\ValueObject\ObjectReleaseManifest;
use App\Objecting\ValueObject\ObjectReleaseReport;

interface ObjectReleaseManifestReporterInterface
{
    public function report(ObjectReleaseManifest $manifest): ObjectReleaseReport;
}
