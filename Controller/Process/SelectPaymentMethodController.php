<?php

namespace Vespolina\StoreBundle\Controller\Process;

use Symfony\Component\HttpFoundation\Request;
use Vespolina\StoreBundle\Controller\Process\AbstractProcessStepController;
use Vespolina\StoreBundle\Form\Type\Process\SelectPaymentMethodFormType;

class SelectPaymentMethodController extends AbstractProcessStepController
{
    public function executeAction()
    {
        $processManager = $this->container->get('vespolina.process_manager');
        $request = $this->container->get('request');
        $selectPaymentMethodForm = $this->createSelectPaymentMethodForm();

        if ($this->isPostForForm($request, $selectPaymentMethodForm)) {

            $selectPaymentMethodForm->bindRequest($request);

            if ($selectPaymentMethodForm->isValid()) {

                $process = $this->processStep->getProcess();
                $this->processStep->getContext()->set('payment_method', $selectPaymentMethodForm->getData());

                //Signal enclosing process step that we are done here
                $process->completeProcessStep($this->processStep);
                $processManager->updateProcess($process);

                return $process->execute();
            } else {
            }
        } else {

            return $this->render('VespolinaStoreBundle:Process:Step/determinePaymentMethod.html.twig',
                array('currentProcessStep' => $this->processStep,
                      'selectPaymentMethodForm' => $selectPaymentMethodForm->createView()));
        }
    }

    protected function createSelectPaymentMethodForm()
    {
        $paymentMethod = $this->processStep->getContext()->get('payment_method');
        $selectPaymentMethodForm = $this->container->get('form.factory')->create(new SelectPaymentMethodFormType($this->getPaymentMethodChoices()), $paymentMethod, array());

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
