<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Vespolina\StoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('vespolina_store');
        $rootNode
            ->children()
            ->scalarNode('db_driver')->cannotBeOverwritten()->isRequired()->cannotBeEmpty()->end()
            ->end();
        $this->addStoresSection($rootNode);
        return $treeBuilder;
    }

    protected function addStoresSection(ArrayNodeDefinition $node)
    {
        $node->children()
            ->arrayNode('stores')
                ->useAttributeAsKey('id')
                ->prototype('array')
                    ->children()
                        ->scalarNode('display_name')->end()
                        ->scalarNode('default')->end()
                        ->scalarNode('legal_name')->end()
                        ->scalarNode('sales_channel')->end()
                        ->scalarNode('operational_mode')->end()
                        ->scalarNode('default_product_view')->end()
                        ->scalarNode('taxation_enabled')->end()
            ->end()
            ->end()
        ->end();

    }
}
