<?php

declare(strict_types=1);

namespace App\Objecting\EntityInterface;

interface ObjectEntityInterface extends ObjectIdentifiedInterface, ObjectTitledInterface, ObjectAuditedInterface, ObjectStatefulInterface
{
}
