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
    protected $session;

    public function __construct($dm, $session) {

        $this->classMap = $this->getClassMap();
        $this->session = $session;
    }

    public function createProcess($name, $owner = null)
    {
        $baseClass = $this->getProcessClass($name);

        $process = new $baseClass($this->container);
        $process->setId(uniqid());

        return $process;
    }

    public function loadProcessFromContext($name, $context) {

        $baseClass = $this->getProcessClass($name);
        $process = new $baseClass($this->container, $context);
        $process->setId($context->get('id'));

        $process->init(false);

        return $process;
    }

    public function findProcessById($processId)
    {
        $processContextFound = null;

        //For now we just use the session

        $processes = $this->session->get('processes', array());
        foreach($processes as $processName => $processContext) {
            if ($processContext->get('id') == $processId) {

                return $this->loadProcessFromContext($processName, $processContext);
            }
        }
    }

    public function getActiveProcessByOwner($name, $owner)
    {

        if ($owner == $this->session->getId()) {


        }

        return null;
    }

    protected function getClassMap()
    {
        return array(
            'checkout_b2c' =>  'Vespolina\StoreBundle\Process\Scenario\CheckoutProcessB2C'
        );
    }

    public function updateProcess(ProcessInterface $process)
    {
        //For now we persist only the context into the session
        $processes = $this->session->get('processes', array());

        $processes[$process->getName()] = $process->getContext();
        $this->session->set('processes', $processes);
    }

     public function getProcessClass($name) {

         return $this->classMap[$name];

     }
}
