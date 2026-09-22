<?php

namespace Kowada\AuthBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Defines the (currently empty) `kowada_auth` configuration tree exposed to consuming applications.
 */
class Configuration implements ConfigurationInterface {

    /**
     * @return TreeBuilder The (currently childless) tree; kept so `kowada_auth` config is still validated.
     */
    public function getConfigTreeBuilder(): TreeBuilder {
        $treeBuilder = new TreeBuilder('kowada_auth');
        $treeBuilder->getRootNode();

        return $treeBuilder;
    }

}
