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
    protected $currentProcessStep;

    public function __construct($container, $context = null)
    {
        $this->container = $container;
        $this->context = $context;

        if (null == $this->context) {
            $this->context = new ArrayCollection();
        }

        $this->definition = new ProcessDefinition();
        $this->eventDispatcher = $container->get('event_dispatcher');
        $this->logger = new Logger('process');
        $this->processSteps = new ArrayCollection();
    }

    public function init($firstTime = false)
    {
        //Build the process model definition
        $this->definition = $this->build();

        if ($firstTime) {
            $this->setState($this->getInitialState());
        }
    }

    public function isCompleted()
    {
        return $this->getState() == 'completed';
    }

    public function execute()
    {
        if ($this->isCompleted()) {
            return;
        }
        if ($currentProcessStep = $this->getCurrentProcessStep()) {

            $result =  $currentProcessStep->execute($this);

            //Fire up the next step using recursion
            if ($currentProcessStep->isCompleted())  {

                //Signal the process that this process step has been completed
                $this->completeProcessStep($currentProcessStep);

                return $this->execute();
            } else {
                //We should have received a response
                return $result;
            }

        } else {

            throw new \Exception('Could not find a process step to execute for state "' . $this->getState() . '"');
        }
    }

    public function executeProcessStep($name)
    {
        $processStep = $this->getProcessStepByName($name);

        if (null != $processStep) {
            return $processStep->execute($this);
        }
    }

    public function gotoProcessStep(ProcessStepInterface $processStep)
    {
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

    public function getLogger()
    {
        return $this->logger;
    }

    public function getProcessSteps()
    {
        $steps = array();

        foreach ($this->definition->getSteps() as $stepConfiguration) {
            $step = $this->getProcessStepByName($stepConfiguration['name']);
            $step->init();
            $steps[] = $step;
        }
        return $steps;
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
        $processStep = $this->processSteps->get($name);

        if (null == $processStep) {

            $data = $this->definition->getStepConfig($name);
            $processStep = new $data['class']($this);
            $this->processSteps->set($name, $processStep);
        }

        return $processStep;
    }

   protected function setState($state)
   {
       $this->context['state'] = $state;
   }
}
