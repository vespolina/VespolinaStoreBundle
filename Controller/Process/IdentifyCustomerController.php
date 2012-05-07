<?php

namespace Vespolina\StoreBundle\Controller\Process;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Vespolina\PartnerBundle\Form\Type\CustomerQuickCreate;
use Vespolina\StoreBundle\Controller\Process\AbstractProcessStepController;

class IdentifyCustomerController extends AbstractProcessStepController
{
    public function executeAction()
    {
        $createCustomerForm = $this->createCustomerQuickCreateForm();

        // We came here because the checkout process 'identify customer' step could not determine the identity of the customer

        return $this->render('VespolinaStoreBundle:Process:Step/identifyCustomer.html.twig',
                    array('currentProcessStep' => $this->processStep,
                          'createCustomerForm' => $createCustomerForm->createView()));
    }

    public function createCustomerAction(Request $request, $processId)
    {

       $createCustomerForm = $this->createCustomerQuickCreateForm();

       if ($request->getMethod() == 'POST') {

            $createCustomerForm->bindRequest($request);

            if ($createCustomerForm->isValid()) {

                $processStep = $this->getCurrentProcessStepByProcessId($processId);
                $process = $processStep->getProcess();

                //Signal process step that it's completed
                $process->completeProcessStep($processStep);

                $processManager = $this->container->get('vespolina.process_manager');
                $processManager->updateProcess($process);

                return $process->execute();

            }
        }

    }

    protected function createCustomerQuickCreateForm()
    {
        $partnerManager = $this->container->get('vespolina.partner_manager');
        $customer = $partnerManager->createPartner();
        $createCustomerForm = $this->container->get('form.factory')->create(new CustomerQuickCreate(), $customer, array());

        return $createCustomerForm;
    }

}
