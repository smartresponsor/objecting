<?php

declare(strict_types=1);

namespace App\Objecting\ServiceInterface\FieldPack;

use App\Objecting\ValueObject\ObjectBackendMigrationCommandPacket;
use App\Objecting\ValueObject\ObjectBackendMigrationCommandReport;

interface ObjectBackendMigrationCommandPacketReporterInterface
{
    public function report(ObjectBackendMigrationCommandPacket $packet): ObjectBackendMigrationCommandReport;
}
