<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Process;

use Doctrine\Common\Collections\ArrayCollection;
use Monolog\Logger;
use Vespolina\StoreBundle\Process\ProcessInterface;
use Vespolina\StoreBundle\Process\ProcessDefinition;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
abstract class AbstractProcess implements ProcessInterface
{
    protected $container;
    protected $context;
    protected $definition;
    protected $eventDispatcher;
    protected $id;
    protected $logger;
    protected $processSteps;

    public function __construct($container, $context)
    {
        $this->container = $container;
        $this->context = $context;

        if (null == $this->context) {
            $this->context = new ArrayCollection();
        }

        $this->definition = new ProcessDefinition();
        $this->eventDispatcher = $container->get('event_dispatcher');

        $this->logger = new Logger('process');
    }

    public function init($firstTime = false)
    {
        $this->definition = $this->build();

        if ($firstTime) {

            $this->setState($this->getInitialState());
        }

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

        if (null != $processStep) {

            return $processStep->execute($this);
        }
    }

    public function gotoProcessStep(ProcessStepInterface $processStep) {

        //Todo: add logic prevent transition to 'locked' steps,
        //eg. step to perform payment should not be repeatable
        $this->context['state'] = $processStep->getName();

        return $this->execute();
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function getEventDispatcher()
    {

        return $this->eventDispatcher;
    }

    public function getLogger() {

        return $this->logger;
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

    public function getProcessStepByName($name)
    {
        foreach($this->processSteps as $processStep) {

            if ($processStep->getName() == $name ) {

                return $processStep;
            }
        }
    }

    protected function loadProcessSteps($firstTime)
    {
        foreach($this->getClassMap() as $processStepClass) {

            $processStep = new $processStepClass($this);
            $processStep->init($firstTime);
            $this->processSteps[] = $processStep;
        }
    }

   protected function setState($state)
   {
       $this->context['state'] = $state;
   }


}
