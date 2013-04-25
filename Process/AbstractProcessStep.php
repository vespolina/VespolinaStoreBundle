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
abstract class AbstractProcessStep implements ProcessStepInterface
{
    protected $displayName;
    protected $name;
    protected $state;
    protected $process;

    public function __construct(ProcessInterface $process)
    {
        $this->process = $process;
        $this->state = 'initial';
    }

    public function complete() {
       $this->state = 'completed';
    }

    public function getContext()
    {
        return $this->process->getContext();
    }

    public function getContainer()
    {
        return $this->process->getContainer();
    }

    public function getLogger()
    {
        return $this->process->getLogger();
    }

    public function getProcess()
    {
        return $this->process;
    }

    protected function getController($class)
    {
        $controller = new $class($this);

        return $controller;
    }

    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    public function getDisplayName()
    {
        return $this->displayName;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function isCompleted()
    {
        return $this->getState() == 'completed';
    }
}
