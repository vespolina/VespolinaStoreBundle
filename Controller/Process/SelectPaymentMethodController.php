<?php

namespace Vespolina\StoreBundle\Controller\Process;

use Symfony\Component\HttpFoundation\Request;
use Vespolina\StoreBundle\Controller\Process\AbstractProcessStepController;
use Vespolina\StoreBundle\Form\Type\Process\SelectPaymentMethod;

class SelectPaymentMethodController extends AbstractProcessStepController
{
    public function executeAction()
    {
        $selectPaymentMethodForm = $this->createSelectPaymentMethodForm();

        // We came here because the checkout process 'identify customer' step could not determine the identity of the customer
        return $this->render('VespolinaStoreBundle:Process:Step/determinePaymentMethod.html.twig',
            array('currentProcessStep' => $this->processStep,
                  'selectPaymentMethodForm' => $selectPaymentMethodForm->createView()));
    }

    public function PaymentMethodSelectedAction(Request $request, $processId)
    {
        $selectPaymentMethodForm = $this->createSelectPaymentMethodForm();
        $processManager = $this->container->get('vespolina.process_manager');

        if ($request->getMethod() == 'POST') {

            $selectPaymentMethodForm->bindRequest($request);

            if ($selectPaymentMethodForm->isValid()) {

                $this->processStep = $this->getCurrentProcessStepByProcessId($processId);
                $process = $this->processStep->getProcess();

                $this->processStep->getContext()->set('payment_method', $selectPaymentMethodForm->getData());

                //Signal enclosing process step that we are done here
                $process->completeProcessStep($this->processStep);
                $processManager->updateProcess($process);

                return $process->execute();
            } else {
            }
        }
    }

    protected function createSelectPaymentMethodForm()
    {
        $selectPaymentMethodForm = $this->container->get('form.factory')->create(new SelectPaymentMethod($this->getPaymentMethodChoices()), array(), array());

        return $selectPaymentMethodForm;
    }

    protected function getPaymentMethodChoices()
    {
        return
            array('pay_pal' => 'Paypal',
                  'credit_card' => 'Credit card',
                  'bank_transfer' => 'Bank transfer');
    }

}
