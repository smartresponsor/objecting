<?php

declare(strict_types=1);

namespace App\Objecting\Service\Reference;

use App\Objecting\ServiceInterface\Reference\ObjectReferenceIntegrityCheckerInterface;
use App\Objecting\ValueObject\ObjectReference;

/**
 * Safe default for isolated components. Real hosts can decorate/replace it.
 */
final class NullObjectReferenceIntegrityChecker implements ObjectReferenceIntegrityCheckerInterface
{
    public function exists(ObjectReference $reference): bool
    {
        return true;
    }
}
