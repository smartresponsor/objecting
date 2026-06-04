<?php

declare(strict_types=1);

namespace App\Objecting\ReporterInterface\FieldPack;

use App\Objecting\Packet\ObjectBackendMigrationCommandPacket;
use App\Objecting\Report\ObjectBackendMigrationCommandReport;

interface ObjectBackendMigrationCommandPacketReporterInterface
{
    public function report(ObjectBackendMigrationCommandPacket $packet): ObjectBackendMigrationCommandReport;
}
