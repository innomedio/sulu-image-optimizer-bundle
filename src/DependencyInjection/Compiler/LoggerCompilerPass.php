<?php

declare(strict_types=1);

namespace Innomedio\Sulu\ImageOptimizerBundle\DependencyInjection\Compiler;

use Innomedio\Sulu\ImageOptimizerBundle\EventListener\ImageUploadRequestListener;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class LoggerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $config = $container->getParameter('innomedio_sulu_image_optimize_config');

        if (is_null($config['logger'])) {
            return;
        }

        $listener = $container->getDefinition(ImageUploadRequestListener::class);
        $listener->setArguments(
            array_merge($listener->getArguments(), ['$logger' => new Reference($config['logger'])])
        );
    }
}
