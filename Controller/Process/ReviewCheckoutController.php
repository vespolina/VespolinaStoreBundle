<?php

namespace Vespolina\StoreBundle\Controller\Process;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Vespolina\StoreBundle\Form\Type\Process\CheckoutReviewFormType;
use Vespolina\StoreBundle\Controller\Process\AbstractProcessStepController;

class ReviewCheckoutController extends AbstractProcessStepController
{

    public function executeAction()
    {
        $processManager = $this->container->get('vespolina.process_manager');
        $request = $this->container->get('request');
        $checkoutReviewForm = $this->createCheckoutReviewedForm();
        if ($this->isPostForForm($request, $checkoutReviewForm)) {

            $checkoutReviewForm->bindRequest($request);

            if ($checkoutReviewForm->isValid()) {

                $process = $this->processStep->getProcess();

                //Signal enclosing process step that we are done here
                $process->completeProcessStep($this->processStep);
                $processManager->updateProcess($process);

                return $process->execute();
            } else {
            }
        } else {

            return $this->render('VespolinaStoreBundle:Process:Step/reviewCheckout.html.twig',
                array('cart' => $this->processStep->getContext()->get('cart'),
                      'context' => $this->processStep->getContext(),
                      'currentProcessStep' => $this->processStep,
                      'checkoutReviewForm' => $checkoutReviewForm->createView()));
        }
    }

    protected function createCheckoutReviewedForm()
    {
        $checkoutReviewForm = $this->container->get('form.factory')->create(new CheckoutReviewFormType(), array(), array());

        return $checkoutReviewForm;
    }


}
