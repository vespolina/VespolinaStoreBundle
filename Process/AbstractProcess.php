<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Process;

use Vespolina\StoreBundle\Process\ProcessInterface;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
abstract class AbstractProcess implements ProcessInterface
{
    protected $classMap;
    protected $container;
    protected $context;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function init()
    {

        $this->classMap = $this->getClassMap();
    }

    public function executeProcessStep($name) {


        $processStep = $this->getProcessStep($name);

        if ($processStep) {

            return $processStep->execute($this);
        }
    }

    public function getContainer()
    {
        return $this->container;
    }

    protected function getProcessStep($name) {

        $class = $this->classMap[$name];
        $processStep = new $class($this);

        return $processStep;
    }



}