<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Process\Scenario;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
class CheckoutProcessB2C
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }



}