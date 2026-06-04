<?php

declare(strict_types=1);

namespace App\Objecting\Reporter\FieldPack;

use App\Objecting\Manifest\ObjectBackendAdoptionManifest;
use App\Objecting\Report\ObjectBackendAdoptionReport;
use App\Objecting\ReporterInterface\FieldPack\ObjectBackendAdoptionManifestReporterInterface;
use App\Objecting\ReporterInterface\FieldPack\ObjectBackendMigrationReadinessReporterInterface;

final readonly class ObjectBackendAdoptionManifestReporter implements ObjectBackendAdoptionManifestReporterInterface
{
    public function __construct(
        private ?ObjectBackendMigrationReadinessReporterInterface $readinessReporter = null,
    ) {
    }

    public function report(ObjectBackendAdoptionManifest $manifest): ObjectBackendAdoptionReport
    {
        $readinessReporter = $this->readinessReporter ?? new ObjectBackendMigrationReadinessReporter();
        $readinessReport = $readinessReporter->report($manifest->toConsumerContract());
        $checks = [];
        $blockingReasons = [];

        $expectedNamespace = 'App\\'.$manifest->component();
        if ($manifest->namespace() !== $expectedNamespace) {
            $blockingReasons[] = sprintf('Backend adoption namespace "%s" must equal "%s".', $manifest->namespace(), $expectedNamespace);
        }
        $checks[] = 'backend_component_namespace';

        $expectedTablePrefix = $this->businessStemToTablePrefix($manifest->businessStem());
        if ($manifest->tableName() !== $expectedTablePrefix && !str_starts_with($manifest->tableName(), $expectedTablePrefix.'_')) {
            $blockingReasons[] = sprintf('Backend adoption table "%s" must use business/entity prefix "%s".', $manifest->tableName(), $expectedTablePrefix);
        }
        $checks[] = 'business_table_prefix';

        foreach ($manifest->fieldPacks() as $fieldPack) {
            if (!in_array($fieldPack, $manifest->effectiveFieldPacks(), true)) {
                $blockingReasons[] = sprintf('Explicit field pack "%s" must be present in effective_field_packs.', $fieldPack);
            }
        }
        $checks[] = 'explicit_packs_in_effective_packs';

        if (null !== $manifest->fieldPackProfile() && $manifest->effectiveFieldPacks() === $manifest->fieldPacks()) {
            $blockingReasons[] = 'Backend adoption manifest declares a field-pack profile but does not expose resolved effective_field_packs.';
        }
        $checks[] = 'profile_resolution_materialized';

        if (null !== $manifest->exposingContractPath()) {
            $expectedContractPrefix = sprintf('contract/component/%s/%s/', $manifest->component(), $manifest->businessStem());
            if (!str_starts_with($manifest->exposingContractPath(), $expectedContractPrefix)) {
                $blockingReasons[] = sprintf('Exposing contract path "%s" must start with "%s".', $manifest->exposingContractPath(), $expectedContractPrefix);
            }
        }
        $checks[] = 'optional_exposing_contract_path';

        if (!$manifest->standaloneReady()) {
            $blockingReasons[] = 'Backend adoption manifest is not marked standalone-ready.';
        }
        $checks[] = 'standalone_ready';

        return new ObjectBackendAdoptionReport(
            manifest: $manifest,
            migrationReadinessReport: $readinessReport,
            checks: $checks,
            blockingReasons: array_values(array_unique(array_merge($blockingReasons, $readinessReport->blockingReasons()))),
        );
    }

    private function businessStemToTablePrefix(string $businessStem): string
    {
        $snake = strtolower((string) preg_replace('/(?<!^)[A-Z]/', '_$0', $businessStem));

        return $snake;
    }
}
