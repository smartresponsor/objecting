<?php

declare(strict_types=1);

namespace App\Objecting\Report;

use App\Objecting\Contract\ObjectResolvedFieldPackConsumerContract;
use App\Objecting\ValueObject\ObjectFieldPackName;

final readonly class ObjectBackendMigrationReadinessReport
{
    public const STATUS_READY = 'ready';
    public const STATUS_BLOCKED = 'blocked';

    /**
     * @param list<string> $requiredBaselineFieldPacks
     * @param list<string> $missingBaselineFieldPacks
     * @param list<string> $checks
     * @param list<string> $blockingReasons
     */
    public function __construct(
        private ObjectResolvedFieldPackConsumerContract $contract,
        private array $requiredBaselineFieldPacks,
        private array $missingBaselineFieldPacks,
        private array $checks,
        private array $blockingReasons,
    ) {
        foreach ($this->requiredBaselineFieldPacks as $fieldPack) {
            if (!ObjectFieldPackName::isKnown($fieldPack)) {
                throw new \InvalidArgumentException(sprintf('Unknown required Objecting baseline field pack "%s".', $fieldPack));
            }
        }

        foreach ($this->missingBaselineFieldPacks as $fieldPack) {
            if (!ObjectFieldPackName::isKnown($fieldPack)) {
                throw new \InvalidArgumentException(sprintf('Unknown missing Objecting baseline field pack "%s".', $fieldPack));
            }
        }

        foreach ($this->checks as $check) {
            if ('' === $check) {
                throw new \InvalidArgumentException('Objecting backend migration readiness check cannot be empty.');
            }
        }

        foreach ($this->blockingReasons as $blockingReason) {
            if ('' === $blockingReason) {
                throw new \InvalidArgumentException('Objecting backend migration blocking reason cannot be empty.');
            }
        }
    }

    public function contract(): ObjectResolvedFieldPackConsumerContract
    {
        return $this->contract;
    }

    /** @return list<string> */
    public function requiredBaselineFieldPacks(): array
    {
        return $this->requiredBaselineFieldPacks;
    }

    /** @return list<string> */
    public function missingBaselineFieldPacks(): array
    {
        return $this->missingBaselineFieldPacks;
    }

    /** @return list<string> */
    public function checks(): array
    {
        return $this->checks;
    }

    /** @return list<string> */
    public function blockingReasons(): array
    {
        return $this->blockingReasons;
    }

    public function status(): string
    {
        return [] === $this->blockingReasons ? self::STATUS_READY : self::STATUS_BLOCKED;
    }

    public function isReady(): bool
    {
        return self::STATUS_READY === $this->status();
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'status' => $this->status(),
            'component' => $this->contract->component(),
            'business_stem' => $this->contract->businessStem(),
            'entity_class' => $this->contract->entityClass(),
            'field_pack_profile' => $this->contract->fieldPackProfile(),
            'explicit_field_packs' => $this->contract->explicitFieldPacks(),
            'effective_field_packs' => $this->contract->effectiveFieldPacks(),
            'required_baseline_field_packs' => $this->requiredBaselineFieldPacks,
            'missing_baseline_field_packs' => $this->missingBaselineFieldPacks,
            'title_alias_profile' => $this->contract->titleAliasProfile(),
            'checks' => $this->checks,
            'blocking_reasons' => $this->blockingReasons,
        ];
    }
}
