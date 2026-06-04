<?php

declare(strict_types=1);

namespace App\Objecting\ReporterInterface\Release;

use App\Objecting\Manifest\ObjectPlatformConstraintManifest;
use App\Objecting\Report\ObjectPlatformConstraintReport;

interface ObjectPlatformConstraintReporterInterface
{
    public function report(ObjectPlatformConstraintManifest $manifest): ObjectPlatformConstraintReport;
}
