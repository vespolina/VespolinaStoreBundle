<?php

namespace Vespolina\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Vespolina\StoreBundle\Controller\AbstractController;
use Vespolina\StoreBundle\Process\ProcessStepInterface;

class ProcessController extends AbstractController
{
    public function processNavigatorAction(ProcessStepInterface $currentProcessStep)
    {
        $process = $currentProcessStep->getProcess();

        return $this->render('VespolinaStoreBundle:Process:processNavigator.html.twig',
            array('currentProcessStep' => $currentProcessStep,
                  'processSteps' => $process->getProcessSteps(),
                  'process'      => $process));
    }


}
