<?php

namespace Vespolina\StoreBundle\Controller\Process;

use Symfony\Component\HttpFoundation\Request;
use Vespolina\StoreBundle\Controller\Process\AbstractProcessStepController;
use Vespolina\StoreBundle\Form\Type\Process\SelectFulfillment;

class DetermineFulfillmentController extends AbstractProcessStepController
{
    public function executeAction()
    {
        $selectFulfillmentForm = $this->createSelectFulfillmentForm();

        // We came here because the checkout process 'identify customer' step could not determine the identity of the customer
        return $this->render('VespolinaStoreBundle:Process:Step/determineFulfillment.html.twig',
            array('currentProcessStep' => $this->processStep,
                  'selectFulfillmentForm' => $selectFulfillmentForm->createView()));
    }

    public function fulfillmentSelectedAction(Request $request, $processId)
    {
        $selectFulfillmentForm = $this->createSelectFulfillmentForm();
        $processManager = $this->container->get('vespolina.process_manager');

        if ($request->getMethod() == 'POST') {

            $selectFulfillmentForm->bindRequest($request);

            if ($selectFulfillmentForm->isValid()) {

                $this->processStep = $this->getCurrentProcessStepByProcessId($processId);
                $process = $this->processStep->getProcess();

                //Signal enclosing process step that we are done here
                $process->completeProcessStep($this->processStep);
                $processManager->updateProcess($process);

                return $process->execute();

            } else {
            }
        }
    }

    protected function createSelectFulfillmentForm()
    {
        $selectFulfillmentForm = $this->container->get('form.factory')->create(new SelectFulfillment($this->getFulfillmentChoices()), null, array());

        return $selectFulfillmentForm;
    }

    protected function getFulfillmentChoices()
    {
        return
            array('fedex' => 'Fedex fast delivery',
                  'dhl' => 'DHL overnight delivery',
                  'collect' => 'Collect the package yourself');
    }

}
