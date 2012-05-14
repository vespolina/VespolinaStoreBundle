<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Process;

use Doctrine\Common\Collections\ArrayCollection;
use Vespolina\StoreBundle\Process\ProcessInterface;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
abstract class AbstractProcess implements ProcessInterface
{
    protected $classMap;
    protected $container;
    protected $context;
    protected $id;
    protected $processSteps;

    public function __construct($container, $context)
    {
        $this->container = $container;
        $this->context = $context;

        if (!$this->context) {
            $this->context = new ArrayCollection();
        }
    }

    public function init($firstTime = false)
    {
        if ($firstTime) {

            $this->setState($this->getInitialState());
        }

        $this->loadProcessSteps($firstTime);
    }

    public function execute()
    {

        if ($currentProcessStep = $this->getCurrentProcessStep()) {

            return $currentProcessStep->execute($this);
        } else {

            throw new \Exception('Could not find a process step to execute');
        }
    }

    public function executeProcessStep($name) {

        $processStep = $this->getProcessStepByName($name);

        if ($processStep) {

            return $processStep->execute($this);
        }
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function getProcessSteps()
    {
        if (!$this->processSteps) {

            $this->loadProcessSteps(false);
        }

        return $this->processSteps;
    }

    public function getState()
    {
        return $this->context['state'];
    }

    public function setId($id)
    {
        $this->id = $id;
        $this->context['id'] = $id;
    }

    public function getId()
    {
        return $this->id;
    }


    protected function loadProcessSteps($firstTime)
    {

        foreach($this->getClassMap() as $processStepClass) {

            $processStep = new $processStepClass($this);
            $processStep->init($firstTime);
            $this->processSteps[] = $processStep;

        }
    }

    protected function getProcessStepByName($name)
    {

        foreach($this->processSteps as $processStep) {
            if ($processStep->getName() == $name ) {

                return $processStep;
            }
        }
        echo 'not found';
   }

   protected function setState($state)
   {
       $this->context['state'] = $state;
   }


}