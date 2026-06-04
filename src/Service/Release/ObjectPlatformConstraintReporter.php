<?php

declare(strict_types=1);

namespace App\Objecting\Service\Release;

use App\Objecting\ServiceInterface\Release\ObjectPlatformConstraintReporterInterface;
use App\Objecting\ValueObject\ObjectPackageSurface;
use App\Objecting\ValueObject\ObjectPlatformConstraintManifest;
use App\Objecting\ValueObject\ObjectPlatformConstraintReport;

final readonly class ObjectPlatformConstraintReporter implements ObjectPlatformConstraintReporterInterface
{
    public function report(ObjectPlatformConstraintManifest $manifest): ObjectPlatformConstraintReport
    {
        $checks = [];
        $blockingReasons = [];

        if (ObjectPackageSurface::COMPOSER_PACKAGE !== $manifest->packageName()) {
            $blockingReasons[] = 'Objecting platform package name must be objecting/object.';
        }
        $checks[] = 'objecting_platform_package_name';

        if ('^8.4' !== $manifest->phpConstraint()) {
            $blockingReasons[] = 'Objecting platform PHP constraint must be ^8.4.';
        }
        $checks[] = 'php_constraint_is_84';

        if ('^8.0' !== $manifest->symfonyConstraint()) {
            $blockingReasons[] = 'Objecting platform Symfony constraint must be ^8.0.';
        }
        $checks[] = 'symfony_constraint_is_80';

        $required = $manifest->requiredConstraints();
        foreach (['php' => '^8.4'] + array_fill_keys(ObjectPackageSurface::SYMFONY_REQUIRE_PACKAGES, '^8.0') as $package => $constraint) {
            if (($required[$package] ?? null) !== $constraint) {
                $blockingReasons[] = sprintf('Objecting composer require constraint for %s must be %s.', $package, $constraint);
            }
        }
        $checks[] = 'composer_require_constraints';

        if (($manifest->extraSymfony()['require'] ?? null) !== '^8.0') {
            $blockingReasons[] = 'Objecting extra.symfony.require must be ^8.0.';
        }
        $checks[] = 'symfony_extra_require_is_80';

        foreach ($manifest->forbiddenConstraints() as $forbiddenConstraint) {
            if (str_contains($forbiddenConstraint, '^7') || str_contains($forbiddenConstraint, '7.')) {
                continue;
            }
            $blockingReasons[] = sprintf('Forbidden platform constraint marker must identify Symfony 7 drift: %s.', $forbiddenConstraint);
        }
        $checks[] = 'forbidden_symfony_7_markers';

        foreach (['composer test:platform-constraints', 'php tools/test/objecting_platform_constraint_check.php'] as $requiredGate) {
            if (!in_array($requiredGate, $manifest->qualityGates(), true)) {
                $blockingReasons[] = sprintf('Objecting platform quality gates must include "%s".', $requiredGate);
            }
        }
        $checks[] = 'platform_quality_gates';

        if (!$manifest->php84Only()) {
            $blockingReasons[] = 'Objecting platform must be PHP 8.4 only.';
        }
        $checks[] = 'php_84_only';

        if (!$manifest->symfony8Only()) {
            $blockingReasons[] = 'Objecting platform must be Symfony 8 only.';
        }
        $checks[] = 'symfony_8_only';

        if (!$manifest->symfony7Forbidden()) {
            $blockingReasons[] = 'Objecting platform must forbid Symfony 7.';
        }
        $checks[] = 'symfony_7_forbidden';

        if (!$manifest->mixedSymfony78Forbidden()) {
            $blockingReasons[] = 'Objecting platform must forbid mixed Symfony 7/8 constraints.';
        }
        $checks[] = 'mixed_symfony_7_8_forbidden';

        if (!$manifest->legacyFree()) {
            $blockingReasons[] = 'Objecting platform constraint layer must stay legacy-free.';
        }
        $checks[] = 'legacy_free';

        return new ObjectPlatformConstraintReport($manifest, $checks, array_values(array_unique($blockingReasons)));
    }
}
