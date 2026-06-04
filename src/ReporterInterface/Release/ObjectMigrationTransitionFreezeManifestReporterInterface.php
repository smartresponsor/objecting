<?php

declare(strict_types=1);

namespace App\Objecting\ReporterInterface\Release;

use App\Objecting\Manifest\ObjectMigrationTransitionFreezeManifest;
use App\Objecting\Report\ObjectMigrationTransitionFreezeReport;

interface ObjectMigrationTransitionFreezeManifestReporterInterface
{
    public function report(ObjectMigrationTransitionFreezeManifest $manifest): ObjectMigrationTransitionFreezeReport;
}
