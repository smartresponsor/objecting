<?php

declare(strict_types=1);

namespace App\Objecting\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class ObjectExtension extends Extension
{
    /** @param array<string, mixed> $configs */
    public function load(array $configs, ContainerBuilder $container): void
    {
        unset($configs);

        $container->setParameter('objecting.package_dir', dirname(__DIR__, 2));
        $container->setParameter('objecting.resource_dir', dirname(__DIR__, 2).'/resources');
        $container->setParameter('objecting.field_pack_manifest', dirname(__DIR__, 2).'/resources/field-pack/manifest.yaml');
        $container->setParameter('objecting.title_alias_manifest', dirname(__DIR__, 2).'/resources/title-alias/manifest.yaml');

        $loader = new YamlFileLoader($container, new FileLocator(dirname(__DIR__, 2).'/config'));
        $loader->load('services.yaml');
    }

    public function getAlias(): string
    {
        return 'objecting';
    }
}
