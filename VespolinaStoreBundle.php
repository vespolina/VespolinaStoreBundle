<?php
/**
 * (c) 2011 Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vespolina\StoreBundle\DependencyInjection\Compiler\StoreHandlerFactoryPass;

class VespolinaStoreBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new StoreHandlerFactoryPass());
    }
}
