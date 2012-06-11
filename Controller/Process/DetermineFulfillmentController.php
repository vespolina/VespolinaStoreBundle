<?php

namespace Vespolina\StoreBundle\Controller\Process;

use Symfony\Component\HttpFoundation\Request;
use Vespolina\StoreBundle\Controller\Process\AbstractProcessStepController;
use Vespolina\StoreBundle\Form\Type\Process\SelectFulfillmentMethodFormType;

class DetermineFulfillmentController extends AbstractProcessStepController
{
    public function executeAction()
    {
        $processManager = $this->container->get('vespolina.process_manager');
        $request = $this->container->get('request');
        $selectFulfillmentForm = $this->createSelectFulfillmentForm();

        if ($this->isPostForForm($request, $selectFulfillmentForm)) {

            $selectFulfillmentForm->bindRequest($request);

            if ($selectFulfillmentForm->isValid()) {

                $process = $this->processStep->getProcess();
                $this->processStep->getContext()->set('fulfillment_method', $selectFulfillmentForm->getData());

                //Signal enclosing process step that we are done here
                $process->completeProcessStep($this->processStep);
                $processManager->updateProcess($process);

                return $process->execute();

            } else {
            }
        } else {

            return $this->render('VespolinaStoreBundle:Process:Step/determineFulfillment.html.twig',
                array('currentProcessStep' => $this->processStep,
                      'selectFulfillmentForm' => $selectFulfillmentForm->createView()));

        }
    }

    protected function createSelectFulfillmentForm()
    {
        $fulfillment = $this->processStep->getContext()->get('fulfillment_method');

        $selectFulfillmentForm = $this->container->get('form.factory')->create(new SelectFulfillmentMethodFormType($this->getFulfillmentChoices()), $fulfillment, array());

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
