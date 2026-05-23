<?php

declare(strict_types=1);

namespace App\Objecting\ServiceInterface\FieldPack;

use App\Objecting\ValueObject\ObjectSiblingPilotMigrationHandoffManifest;
use App\Objecting\ValueObject\ObjectSiblingPilotMigrationHandoffReport;

interface ObjectSiblingPilotMigrationHandoffReporterInterface
{
    public function report(ObjectSiblingPilotMigrationHandoffManifest $manifest): ObjectSiblingPilotMigrationHandoffReport;
}
