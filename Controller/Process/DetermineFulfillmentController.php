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
        $this->loadProcess($processId);


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

        // Use the fulfillment method resolver to get back a list of supported fulfillment methods
        $fulfillmentMethodResolver = $this->container->get('vespolina.fulfillment.fulfillment_method_resolver');
        $cart = $this->processStep->getContext()->get('cart');

        //Collect all cartable items from the cart
        $cartableItems = array();
        $fulfillmentChoices = array();

        foreach($cart->getItems() as $cartItem) {
            $cartableItems[] = $cartItem->getCartableItem();
        }

        $fulfillmentMethods = $fulfillmentMethodResolver->resolveFulfillmentMethods($cartableItems, null);

        foreach($fulfillmentMethods as $fulfillmentMethod) {

            $fulfillmentChoices[$fulfillmentMethod->getName()] = $fulfillmentMethod->getDescription();
        }

        return $fulfillmentChoices;
    }

    protected function loadProcess($processId) {

        if (!$this->processStep) {
            $this->processStep = $this->getCurrentProcessStepByProcessId($processId);
        }
    }

}
