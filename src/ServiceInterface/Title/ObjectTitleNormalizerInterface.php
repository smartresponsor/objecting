<?php

declare(strict_types=1);

namespace App\Objecting\ServiceInterface\Title;

interface ObjectTitleNormalizerInterface
{
    public function normalize(?string $value): ?string;
}
