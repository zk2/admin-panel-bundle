<?php

namespace Zk2\Bundle\AdminPanelBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('zk2_admin_panel');

        $rootNode
            ->children()
                ->scalarNode('pagination_template')
                    ->defaultValue('Zk2AdminPanelBundle:AdminPanel:pagination.html.twig')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('sortable_template')
                    ->defaultValue('Zk2AdminPanelBundle:AdminPanel:sortable.html.twig')
                    ->cannotBeEmpty()
                ->end()
                ->booleanNode('check_flag_super_admin')
                    ->defaultFalse()
                ->end()
                ->booleanNode('convert_time_with_timezone')
                    ->defaultTrue()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
