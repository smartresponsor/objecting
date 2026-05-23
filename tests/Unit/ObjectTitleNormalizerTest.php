<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Unit;

use App\Objecting\Service\Title\ObjectTitleNormalizer;
use PHPUnit\Framework\TestCase;

final class ObjectTitleNormalizerTest extends TestCase
{
    public function testNormalizerCollapsesWhitespaceAndConvertsBlankToNull(): void
    {
        $normalizer = new ObjectTitleNormalizer();
        self::assertSame('Alpha Beta', $normalizer->normalize("  Alpha\n\tBeta  "));
        self::assertNull($normalizer->normalize('   '));
        self::assertNull($normalizer->normalize(null));
    }
}
