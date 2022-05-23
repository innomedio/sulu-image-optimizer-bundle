<?php

declare(strict_types=1);

namespace Innomedio\Sulu\ImageOptimizerBundle;

use Innomedio\Sulu\ImageOptimizerBundle\DependencyInjection\Compiler\LoggerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class InnomedioSuluImageOptimizerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new LoggerCompilerPass());
    }
}
