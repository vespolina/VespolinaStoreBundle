<?php

namespace Vespolina\StoreBundle\Controller\Process;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Vespolina\StoreBundle\Form\Type\Process\CustomerQuickCreate;
use Vespolina\StoreBundle\Controller\Process\AbstractProcessStepController;

class ReviewCheckoutController extends AbstractProcessStepController
{
    public function executeAction()
    {

        return $this->render('VespolinaStoreBundle:Process:Step/reviewCheckout.html.twig',
                    array('currentProcessStep' => $this->processStep,
                          'cart' => $this->getProcessStep()->getContext()->get('cart')));
    }

    public function checkoutReviewedAction($processId)
    {
        $processManager = $this->container->get('vespolina.process_manager');
        $this->processStep = $this->getCurrentProcessStepByProcessId($processId);
        $process = $this->processStep->getProcess();

        //Signal enclosing process step that we are done here
        $process->completeProcessStep($this->processStep);
        $processManager->updateProcess($process);

        return $process->execute();
    }


}
