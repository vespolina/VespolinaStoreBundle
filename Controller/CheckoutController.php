<?php

/**
 * (c) 2011 - ∞ Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerAware;
use Vespolina\CommerceBundle\Process\ProcessInterface;

/**
 * @author Richard D Shank <develop@zestic.com>
 */
class CheckoutController extends AbstractController
{
    public function checkoutAction()
    {
        $processManager = $this->container->get('vespolina.process_manager');
        $processOwner = $this->container->get('session')->getId();

        $checkoutProcess = $processManager->getActiveProcessByOwner('checkout_b2c', $processOwner);
        if (null == $checkoutProcess) {
            $checkoutProcess = $processManager->createProcess('checkout_b2c', $processOwner);
            $checkoutProcess->init(true);   //initialize with first time set to true
            $context = $checkoutProcess->getContext();
            $context['cart'] = $this->getCart();
        }
        $processResult = $checkoutProcess->execute();
        $processManager->updateProcess($checkoutProcess);

        if (null != $processResult) {

            return $processResult;
        }

        // If we get here then there was a serious error
        throw new \Exception('Checkout failed - internal error - could not find process step to execute for current state ' . $checkoutProcess->getState());
    }

    public function executeAction($processId, $processStepName)
    {
        $processManager = $this->container->get('vespolina.process_manager');
        $process =  $processManager->findProcessById($processId);

        if (null === $process) {
            throw new \Exception('Process session expired');
        }

        if ($process->isCompleted()) {
            return $this->handleProcessCompletion($process);
        }

        $processStep = $process->getProcessStepByName($processStepName);

        //Assert that the current process step (according to the process) is the same as the step name
        //passed on to the request
        if ($processStep->getName() != $processStepName) {
           throw new \Exception(sprintf(
               'Checkout failed - process step names differ : "%s" != "%s"',
               $processStep->getName(),
               $processStepName));
        }

        return $processStep->getProcess()->execute();
    }

    public function gotoProcessStepAction($processId, $processStepName)
    {
        $processManager = $this->container->get('vespolina.process_manager');
        $process = $processManager->findProcessById($processId);
        $processStep = $process->getProcessStepByName($processStepName);

        return $process->gotoProcessStep($processStep);
    }

    protected function getCart()
    {
        return $this->container->get('vespolina_order.cart_provider')->getOpenCart();
    }

    protected function handleProcessCompletion(ProcessInterface $process)
    {
        return new RedirectResponse('/', 302);
    }

    protected function getEngine()
    {
        return $this->container->getParameter('vespolina.checkout.template_engine');
    }
}
