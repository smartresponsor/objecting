<?php

declare(strict_types=1);

namespace App\Objecting\Tests\Support;

use App\Objecting\EntityTrait\Embeddable\ObjectAuditEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectCodeEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectConfigEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectFingerprintEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectIdentityEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectLocaleEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectLockEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectPublicationEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectRestrictionEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectSoftDeleteEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectSourceEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectStateEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectTitleEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectTokenEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectVersionEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectWorkflowEmbeddableTrait;

final class ObjectEmbeddableTraitUsage
{
    use ObjectAuditEmbeddableTrait;
    use ObjectCodeEmbeddableTrait;
    use ObjectConfigEmbeddableTrait;
    use ObjectFingerprintEmbeddableTrait;
    use ObjectIdentityEmbeddableTrait;
    use ObjectLocaleEmbeddableTrait;
    use ObjectLockEmbeddableTrait;
    use ObjectPublicationEmbeddableTrait;
    use ObjectRestrictionEmbeddableTrait;
    use ObjectSoftDeleteEmbeddableTrait;
    use ObjectSourceEmbeddableTrait;
    use ObjectStateEmbeddableTrait;
    use ObjectTitleEmbeddableTrait;
    use ObjectTokenEmbeddableTrait;
    use ObjectVersionEmbeddableTrait;
    use ObjectWorkflowEmbeddableTrait;
}
