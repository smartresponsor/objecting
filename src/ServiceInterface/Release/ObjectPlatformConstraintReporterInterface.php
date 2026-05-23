<?php

declare(strict_types=1);

namespace App\Objecting\ServiceInterface\Release;

use App\Objecting\ValueObject\ObjectPlatformConstraintManifest;
use App\Objecting\ValueObject\ObjectPlatformConstraintReport;

interface ObjectPlatformConstraintReporterInterface
{
    public function report(ObjectPlatformConstraintManifest $manifest): ObjectPlatformConstraintReport;
}
