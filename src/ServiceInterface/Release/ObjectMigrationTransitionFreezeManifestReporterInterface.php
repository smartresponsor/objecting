<?php

declare(strict_types=1);

namespace App\Objecting\ServiceInterface\Release;

use App\Objecting\ValueObject\ObjectMigrationTransitionFreezeManifest;
use App\Objecting\ValueObject\ObjectMigrationTransitionFreezeReport;

interface ObjectMigrationTransitionFreezeManifestReporterInterface
{
    public function report(ObjectMigrationTransitionFreezeManifest $manifest): ObjectMigrationTransitionFreezeReport;
}
