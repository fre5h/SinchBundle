<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Genvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fresh\SinchBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that loads and manages bundle configuration
 *
 * @author Artem Genvald  <genvaldartem@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('fresh_sinch');

        $rootNode
            ->children()
                ->scalarNode('host')->defaultValue('https://messagingapi.sinch.com')->end()
                ->scalarNode('key')->end()
                ->scalarNode('secret')->end()
            ->end();

        return $treeBuilder;
    }
}
