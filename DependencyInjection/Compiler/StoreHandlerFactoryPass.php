<?php

/**
 * (c) 2012 Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class StoreHandlerFactoryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!extension_loaded('intl')) {
            throw new \Exception ("You must have the php extension \"intl\" installed");
        }
    }
}
