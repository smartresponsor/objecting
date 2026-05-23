<?php

declare(strict_types=1);

namespace App\Objecting\ServiceInterface\FieldPack;

use App\Objecting\ValueObject\ObjectBackendHandoffManifest;
use App\Objecting\ValueObject\ObjectBackendHandoffReport;

interface ObjectBackendHandoffManifestReporterInterface
{
    public function report(ObjectBackendHandoffManifest $manifest): ObjectBackendHandoffReport;
}
