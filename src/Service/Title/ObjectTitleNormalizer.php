<?php

declare(strict_types=1);

namespace App\Objecting\Service\Title;

use App\Objecting\ServiceInterface\Title\ObjectTitleNormalizerInterface;

final class ObjectTitleNormalizer implements ObjectTitleNormalizerInterface
{
    public function normalize(?string $value): ?string
    {
        if (null === $value) {
            return null;
        }
        $normalized = trim(preg_replace('/\s+/u', ' ', $value) ?? $value);

        return '' === $normalized ? null : $normalized;
    }
}
