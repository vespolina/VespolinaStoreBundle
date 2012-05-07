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
    protected $id;
    protected $processSteps;

    public function __construct($container, $context = array())
    {
        $this->container = $container;
        $this->context = $context;
    }

    public function init()
    {

        $this->classMap = $this->getClassMap();
        $this->loadProcessSteps();
    }

    public function executeProcessStep($name) {

        $processStep = $this->getProcessStepByName($name);

        if ($processStep) {

            return $processStep->execute($this);
        }
    }

    abstract function getCurrentProcessStep();


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

            $this->loadProcessSteps();
        }

        return $this->processSteps;
    }


    protected function loadProcessSteps()
    {

        foreach($this->getClassMap() as $processStepClass) {

            $processStep = new $processStepClass($this);
            $processStep->init();
            $this->processSteps[] = $processStep;

        }
    }

    protected function getProcessStepByName($name) {

        foreach($this->processSteps as $processStep) {
            if ($processStep->getName() == $name ) {

                return $processStep;
            }
        }
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


}