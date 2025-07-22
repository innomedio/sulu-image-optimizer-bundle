<?php

declare(strict_types=1);

namespace Innomedio\Sulu\ImageOptimizerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('innomedio_sulu_image_optimize_config');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('logger')
                    ->defaultNull()
                ->end()
                ->booleanNode('enabled')
                    ->defaultTrue()
                ->end()
                ->arrayNode('resize')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')
                            ->defaultTrue()
                        ->end()
                        ->integerNode('max_size')
                            ->defaultValue(4000)
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('ignore_types')
                    ->prototype('scalar')->end()
                    ->defaultValue([])
                    ->info('A list of image types to exclude from optimization, which can be useful for formats like GIFs that often don\'t benefit from resizing.')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
