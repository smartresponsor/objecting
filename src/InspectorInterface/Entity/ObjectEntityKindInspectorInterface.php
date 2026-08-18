<?php

declare(strict_types=1);

namespace App\Objecting\InspectorInterface\Entity;

use Doctrine\ORM\Mapping\ClassMetadata;

interface ObjectEntityKindInspectorInterface
{
    /**
     * @param class-string               $entityClass
     * @param ClassMetadata<object>|null $metadata
     *
     * @return list<string>
     */
    public function inspect(string $entityClass, ?ClassMetadata $metadata = null): array;
}
