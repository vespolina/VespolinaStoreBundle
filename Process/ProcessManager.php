<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Vespolina\StoreBundle\Process;

use Symfony\Component\DependencyInjection\ContainerAware;

use Vespolina\StoreBundle\Process\ProcessInterface;
use Vespolina\StoreBundle\Process\ProcessManagerInterface;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
 class ProcessManager extends ContainerAware implements ProcessManagerInterface {

    protected $classMap;

    public function __construct() {

        $this->classMap = $this->getClassMap();
    }



    public function createProcess($name)
    {
        $baseClass = $this->getProcessClass($name);

        $process = new $baseClass($this->container);

        return $process;
    }



    public function getActiveProcessByOwner($name, $owner)
    {
        return null;
    }


    protected function getClassMap()
    {
        return array(
            'checkout_b2c' =>  'Vespolina\StoreBundle\Process\CheckoutProcessB2C'
        );
    }

     public function getProcessClass($name) {

         return $this->classMap[$name];

     }
}
