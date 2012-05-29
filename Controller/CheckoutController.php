<?php

namespace Vespolina\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Vespolina\StoreBundle\Controller\AbstractController;

class CheckoutController extends AbstractController
{
    public function checkoutAction()
    {
        $processManager = $this->container->get('vespolina.process_manager');
        $processOwner = $this->container->get('session')->getId();

        $checkoutProcess = $processManager->getActiveProcessByOwner('checkout_b2c', $processOwner);

        if (!$checkoutProcess) {

            $checkoutProcess = $processManager->createProcess('checkout_b2c', $processOwner);
            $checkoutProcess->init(true);   //initialize with first time set to true

            $context = $checkoutProcess->getContext();
            $context['cart'] = $this->getCart();
            $processResult = $checkoutProcess->execute();

            //Persist (in session)
            $processManager->updateProcess($checkoutProcess);

        } else{
            $checkoutProcess->init();
        }
        if ($processResult) {

            return $processResult;
        }

        //If we get here then there was a serious error
        throw new \Exception('Checkout failed - inernal error - could not find process step to execute');
    }

    public function executeAction($processId, $processStepName) {

        $processManager = $this->container->get('vespolina.process_manager');
        $processStep = $this->getCurrentProcessStepByProcessId($processId);

        //Assert that the current process step (according to the process) is the same as the step name
        //passed on to the request
        if ($processStep->getName() != $processStepName) {
           throw new \Exception('Checkout failed - internal error');
        }

        return $processStep->getProcess()->execute();
    }

    public function gotoProcessStepAction($processId, $processStepName) {

        $processManager = $this->container->get('vespolina.process_manager');
        $process = $processManager->findProcessById($processId);
        $processStep = $process->getProcessStepByName($processStepName);

        return $process->gotoProcessStep($processStep);

    }


    protected function getCart($cartId = null)
    {
        if ($cartId) {
            return $this->container->get('vespolina.cart_manager')->findCartById($cartId);
        } else {
            return $this->container->get('vespolina.cart_manager')->getActiveCart();
        }
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
