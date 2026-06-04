<?php

declare(strict_types=1);

namespace App\Objecting\ReporterInterface\FieldPack;

use App\Objecting\Manifest\ObjectSiblingPilotMigrationHandoffManifest;
use App\Objecting\Report\ObjectSiblingPilotMigrationHandoffReport;

interface ObjectSiblingPilotMigrationHandoffReporterInterface
{
    public function report(ObjectSiblingPilotMigrationHandoffManifest $manifest): ObjectSiblingPilotMigrationHandoffReport;
}
