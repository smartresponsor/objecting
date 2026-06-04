<?php

declare(strict_types=1);

namespace App\Objecting\Reporter\FieldPack;

use App\Objecting\Contract\ObjectFieldPackConsumerContract;
use App\Objecting\Registry\FieldPack\ObjectFieldPackRegistry;
use App\Objecting\Registry\Title\ObjectTitleAliasProfileRegistry;
use App\Objecting\RegistryInterface\FieldPack\ObjectFieldPackRegistryInterface;
use App\Objecting\RegistryInterface\Title\ObjectTitleAliasProfileRegistryInterface;
use App\Objecting\Report\ObjectBackendMigrationReadinessReport;
use App\Objecting\ReporterInterface\FieldPack\ObjectBackendMigrationReadinessReporterInterface;
use App\Objecting\Resolver\FieldPack\ObjectFieldPackConsumerContractResolver;
use App\Objecting\ResolverInterface\FieldPack\ObjectFieldPackConsumerContractResolverInterface;
use App\Objecting\ValueObject\ObjectFieldPackName;

final readonly class ObjectBackendMigrationReadinessReporter implements ObjectBackendMigrationReadinessReporterInterface
{
    /** @var list<string> */
    public const REQUIRED_BASELINE_FIELD_PACKS = [
        ObjectFieldPackName::IDENTITY,
        ObjectFieldPackName::AUDIT,
        ObjectFieldPackName::TITLE,
    ];

    public function __construct(
        private ?ObjectFieldPackConsumerContractResolverInterface $contractResolver = null,
        private ?ObjectFieldPackRegistryInterface $fieldPackRegistry = null,
        private ?ObjectTitleAliasProfileRegistryInterface $titleAliasProfileRegistry = null,
    ) {
    }

    public function report(ObjectFieldPackConsumerContract $contract): ObjectBackendMigrationReadinessReport
    {
        $contractResolver = $this->contractResolver ?? new ObjectFieldPackConsumerContractResolver();
        $fieldPackRegistry = $this->fieldPackRegistry ?? new ObjectFieldPackRegistry();
        $titleAliasProfileRegistry = $this->titleAliasProfileRegistry ?? new ObjectTitleAliasProfileRegistry();

        $resolved = $contractResolver->resolve($contract);
        $blockingReasons = [];
        $checks = [];

        foreach ($resolved->effectiveFieldPacks() as $fieldPack) {
            if (!$fieldPackRegistry->has($fieldPack)) {
                $blockingReasons[] = sprintf('Effective Objecting field pack "%s" is not registered.', $fieldPack);
            }
        }
        $checks[] = 'effective_field_packs_registered';

        $missingBaseline = array_values(array_filter(
            self::REQUIRED_BASELINE_FIELD_PACKS,
            static fn (string $fieldPack): bool => !$resolved->usesFieldPack($fieldPack),
        ));
        if ([] !== $missingBaseline) {
            $blockingReasons[] = sprintf('Objecting consumer is missing baseline field packs: %s.', implode(', ', $missingBaseline));
        }
        $checks[] = 'baseline_identity_audit_title_present';

        if (!str_starts_with($resolved->entityClass(), 'App\\'.$resolved->component().'\\Entity\\')) {
            $blockingReasons[] = sprintf('Entity class "%s" must use the component namespace App\\%s\\Entity\\*.', $resolved->entityClass(), $resolved->component());
        }
        $checks[] = 'component_entity_namespace';

        if (!str_ends_with($resolved->entityClass(), '\\'.$resolved->businessStem())) {
            $blockingReasons[] = sprintf('Entity class "%s" must end with business stem "%s".', $resolved->entityClass(), $resolved->businessStem());
        }
        $checks[] = 'business_stem_entity_suffix';

        if (null !== $resolved->titleAliasProfile() && !$titleAliasProfileRegistry->has($resolved->titleAliasProfile())) {
            $blockingReasons[] = sprintf('Title alias profile "%s" is not registered.', $resolved->titleAliasProfile());
        }
        $checks[] = 'title_alias_profile_registered';

        return new ObjectBackendMigrationReadinessReport(
            contract: $resolved,
            requiredBaselineFieldPacks: self::REQUIRED_BASELINE_FIELD_PACKS,
            missingBaselineFieldPacks: $missingBaseline,
            checks: $checks,
            blockingReasons: $blockingReasons,
        );
    }
}
