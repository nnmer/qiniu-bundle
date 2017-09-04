<?php

namespace Nnmer\QiniuBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('nnmer_qiniu');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode
        ->children()
            ->scalarNode('accessKey')
                ->cannotBeEmpty()
            ->end()
            ->scalarNode('secretKey')
                ->cannotBeEmpty()
            ->end()
            ->scalarNode('defaultBucket')
                ->cannotBeEmpty()
            ->end()
            ->arrayNode('buckets')
                ->requiresAtLeastOneElement()
                ->prototype('scalar')->end()
            ->end()
            ->arrayNode('initiateAdapters')
                ->prototype('scalar')->end()
            ->end()
        ->end()
        ;

        return $treeBuilder;
    }
}
