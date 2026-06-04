<?php

declare(strict_types=1);

namespace App\Objecting\NormalizerInterface\Title;

interface ObjectTitleNormalizerInterface
{
    public function normalize(?string $value): ?string;
}
