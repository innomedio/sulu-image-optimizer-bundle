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
            ->end()
        ;

        return $treeBuilder;
    }
}
