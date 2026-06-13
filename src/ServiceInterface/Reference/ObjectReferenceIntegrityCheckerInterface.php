<?php

declare(strict_types=1);

namespace App\Objecting\ServiceInterface\Reference;

use App\Objecting\ValueObject\ObjectReference;

interface ObjectReferenceIntegrityCheckerInterface
{
    /**
     * Returns true when the referenced aggregate exists and is visible to the current boundary.
     */
    public function exists(ObjectReference $reference): bool;
}
