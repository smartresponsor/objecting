<?php

declare(strict_types=1);

namespace Gating\Gate\Rule\Canon;

use Gating\Gate\Contract\RuleContext;
use Gating\Gate\Contract\RuleResult;

/**
 * Enforces the canonical dev-master constraint for locally linked first-party Composer packages.
 */
final class Canon043DevelopmentComposerDependencyVersionRule extends AbstractCanonRule
{
    public function id(): string
    {
        return 'canon.043.development_composer_dependency_version';
    }

    public function check(RuleContext $context): RuleResult
    {
        $composerPath = $context->targetPath.'/composer.json';
        if (!is_file($composerPath)) {
            return $this->result('skipped', 'Target has no composer.json.');
        }

        $composer = json_decode((string) file_get_contents($composerPath), true);
        if (!is_array($composer)) {
            return $this->result('failed', 'composer.json is invalid.');
        }

        $requirements = [];
        foreach (['require', 'require-dev'] as $section) {
            if (!is_array($composer[$section] ?? null)) {
                continue;
            }

            foreach ($composer[$section] as $package => $constraint) {
                if (is_string($package) && is_string($constraint)) {
                    $requirements[$package] = $constraint;
                }
            }
        }

        $hits = [];
        foreach (($composer['repositories'] ?? []) as $repository) {
            if (!is_array($repository) || 'path' !== ($repository['type'] ?? null)) {
                continue;
            }

            $url = $repository['url'] ?? null;
            if (!is_string($url) || !str_starts_with(str_replace('\\', '/', $url), '../')) {
                continue;
            }

            $siblingPath = realpath($context->targetPath.DIRECTORY_SEPARATOR.$url);
            if (false === $siblingPath) {
                $hits[] = 'Local path repository '.$url.' cannot be resolved.';
                continue;
            }

            $siblingComposerPath = $siblingPath.DIRECTORY_SEPARATOR.'composer.json';
            if (!is_file($siblingComposerPath)) {
                $hits[] = 'Local path repository '.$url.' has no composer.json.';
                continue;
            }

            $siblingComposer = json_decode((string) file_get_contents($siblingComposerPath), true);
            $package = is_array($siblingComposer) ? ($siblingComposer['name'] ?? null) : null;
            if (!is_string($package) || '' === trim($package)) {
                $hits[] = 'Local path repository '.$url.' has no valid Composer package name.';
                continue;
            }

            $constraint = $requirements[$package] ?? null;
            if (null === $constraint) {
                continue;
            }

            if ('dev-master' !== $constraint) {
                $hits[] = $package.' must use dev-master for local path development; found '.$constraint.'.';
            }
        }

        return [] === $hits
            ? $this->result('passed', 'Local sibling Composer dependencies use canonical dev-master constraints.')
            : $this->result('failed', 'Development Composer dependency version policy violations found.', $hits);
    }
}
