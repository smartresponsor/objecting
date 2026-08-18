<?php

declare(strict_types=1);

use App\Objecting\EntityInterface\ObjectEntityInterface;
use App\Objecting\EntityTrait\Embeddable\ObjectAuditEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectIdentityEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectStateEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectTitleEmbeddableTrait;
use App\Objecting\Inspector\Entity\ObjectEntityKindInspector;
use App\Objecting\Registry\Entity\ObjectEntityKindRegistry;
use App\Objecting\Registry\FieldPack\ObjectFieldPackRegistry;

require dirname(__DIR__, 2).'/vendor/autoload.php';

final class ObjectEntityKindCompliantProbe implements ObjectEntityInterface
{
    use ObjectIdentityEmbeddableTrait;
    use ObjectTitleEmbeddableTrait;
    use ObjectAuditEmbeddableTrait;
    use ObjectStateEmbeddableTrait;
}

final class ObjectEntityKindMissingTitleProbe implements ObjectEntityInterface
{
    use ObjectIdentityEmbeddableTrait;
    use ObjectAuditEmbeddableTrait;
    use ObjectStateEmbeddableTrait;

    public function getFirstTitle(): ?string
    {
        return null;
    }

    public function setFirstTitle(?string $firstTitle): void
    {
        unset($firstTitle);
    }

    public function getMiddleTitle(): ?string
    {
        return null;
    }

    public function setMiddleTitle(?string $middleTitle): void
    {
        unset($middleTitle);
    }

    public function getLastTitle(): ?string
    {
        return null;
    }

    public function setLastTitle(?string $lastTitle): void
    {
        unset($lastTitle);
    }
}

$root = dirname(__DIR__, 2);
$registry = new ObjectEntityKindRegistry($root.'/resources/entity-kind/manifest.yaml');
$inspector = new ObjectEntityKindInspector($registry, new ObjectFieldPackRegistry());
$errors = [];

if (['object'] !== $registry->kindsForClass(ObjectEntityKindCompliantProbe::class)) {
    $errors[] = 'Compliant probe did not resolve to the object entity kind.';
}

$compliantErrors = $inspector->inspect(ObjectEntityKindCompliantProbe::class);
if ([] !== $compliantErrors) {
    $errors[] = 'Compliant object entity failed inspection: '.implode('; ', $compliantErrors);
}

$missingTitleErrors = $inspector->inspect(ObjectEntityKindMissingTitleProbe::class);
if (!array_filter($missingTitleErrors, static fn (string $error): bool => str_contains($error, ObjectTitleEmbeddableTrait::class))) {
    $errors[] = 'Inspector did not reject a manually implemented title surface without the canonical title trait.';
}

if ($errors !== []) {
    echo "Objecting entity-kind policy check failed:\n";
    foreach ($errors as $error) {
        echo ' - '.$error."\n";
    }
    exit(1);
}

echo "Objecting entity-kind policy check passed.\n";
