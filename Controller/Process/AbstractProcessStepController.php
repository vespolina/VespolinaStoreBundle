<?php

namespace Vespolina\StoreBundle\Controller\Process;

use Vespolina\StoreBundle\Controller\AbstractController;
use Vespolina\StoreBundle\Process\ProcessStepInterface;

class AbstractProcessStepController extends AbstractController
{
    protected $processStep;


    public function completeProcessStep()
    {
        $process = $this->processStep->getProcess();
        $process->completeProcessStep($this->processStep);

        return $process->execute();
    }

    public function isPostForForm($request, $form) {

        return $request->request->has($form->getName());
    }

    public function setProcessStep(ProcessStepInterface $processStep)
    {
        $this->processStep = $processStep;
    }

    public function getProcessStep()
    {
        return $this->processStep;
    }

    protected function getCurrentProcessStepByProcessId($processId)
    {
        $processManager = $this->container->get('vespolina.process_manager');
        $process = $processManager->findProcessById($processId);
        if ($process) {

            return $process->getCurrentProcessStep();
        }
    }
}
