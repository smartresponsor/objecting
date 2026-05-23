<?php

declare(strict_types=1);

namespace App\Objecting\ServiceInterface\Release;

use App\Objecting\ValueObject\ObjectReleaseClosureManifest;
use App\Objecting\ValueObject\ObjectReleaseClosureReport;

interface ObjectReleaseClosureManifestReporterInterface
{
    public function report(ObjectReleaseClosureManifest $manifest): ObjectReleaseClosureReport;
}
