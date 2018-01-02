<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Henvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Fresh\SinchBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration.
 *
 * @author Artem Henvald  <genvaldartem@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('fresh_sinch');

        $rootNode
            ->children()
                ->scalarNode('host')->defaultValue('https://messagingapi.sinch.com')->end()
                ->scalarNode('key')->end()
                ->scalarNode('secret')->end()
                ->scalarNode('from')->defaultNull()->end()
            ->end();

        return $treeBuilder;
    }
}
