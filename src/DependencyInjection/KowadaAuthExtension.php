<?php

namespace Kowada\AuthBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Loads the bundle's service definitions and validates the (currently empty) `kowada_auth` configuration tree.
 */
class KowadaAuthExtension extends Extension {

    /**
     * @param array<int, array<string, mixed>> $configs Raw configuration arrays from all `kowada_auth` config trees.
     */
    public function load(array $configs, ContainerBuilder $container): void {
        $configuration = new Configuration();

        $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));

        $loader->load('services.yaml');
    }

}
