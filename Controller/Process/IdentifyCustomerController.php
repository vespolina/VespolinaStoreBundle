<?php

namespace Vespolina\StoreBundle\Controller\Process;

use Symfony\Component\Validator\Exception\ValidatorException;

use Vespolina\PartnerBundle\Form\QuickCustomerType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Vespolina\StoreBundle\Form\Type\Process\CustomerQuickCreate;
use Vespolina\StoreBundle\Controller\Process\AbstractProcessStepController;

class IdentifyCustomerController extends AbstractProcessStepController
{
    public function executeAction()
    {

        //First attempt to load the customer from the process container
        $customer = $this->processStep->getContext()->get('customer');

        if (!$customer) {
            //If this fails get it from the user session
            if ($customer = $this->container->get('session')->get('customer')) {
                $this->processStep->getContext()->set('customer', $customer);
            }
        }

        if ($customer) {
            return $this->completeProcessStep();
        }

        // We came here because the checkout process 'identify customer' step could not determine the identity of the customer
        $createCustomerForm = $this->createCustomerQuickCreateForm();

        return $this->render('VespolinaStoreBundle:Process:Step/identifyCustomer.html.twig',
                    array('currentProcessStep' => $this->processStep,
                          'createCustomerForm' => $createCustomerForm->createView(),
                          'last_username' => ''));
    }

    public function createCustomerAction(Request $request, $processId)
    {

        $createCustomerForm = $this->createCustomerQuickCreateForm();
        $partnerManager = $this->container->get('vespolina.partner_manager');
        $processManager = $this->container->get('vespolina.process_manager');

        if ($request->getMethod() == 'POST') {

            $createCustomerForm->bindRequest($request);
            
            // retrieve process
            $this->processStep = $this->getCurrentProcessStepByProcessId($processId);
            $process = $this->processStep->getProcess();
            
            if ($createCustomerForm->isValid()) {
                
                $customerAddressForm = $createCustomerForm->get('address');
                $customerDetailsForm = $createCustomerForm->get('personalDetails');
                // get address and personal details forms
                // FIXME: seems wrong?
                $customerAddress = $customerAddressForm->getData();
                $customerDetails = $customerDetailsForm->getData();
                
                $customer = $createCustomerForm->getData();
                
                $customer->addAddress($customerAddress);
                $customer->setPersonalDetails($customerDetails);
                $customer->setName($partnerManager->generatePartnerName($customerDetails));


                //Save into process context & session
                $process->getContext()->set('customer', $customer);
                $this->container->get('session')->set('customer', $customer);

                $this->container->get('vespolina.partner_manager')->updatePartner($customer);
                
                //Signal enclosing process step that we are done here
                $process->completeProcessStep($this->processStep);
                $processManager->updateProcess($process);

                return $process->execute();

            }
        }
        
        return $this->render('VespolinaStoreBundle:Process:Step/identifyCustomer.html.twig',
                    array('currentProcessStep' => $this->processStep,
                          'createCustomerForm' => $createCustomerForm->createView()));

    }

    protected function createCustomerQuickCreateForm()
    {
        $partnerManager = $this->container->get('vespolina.partner_manager');
        $customer = $partnerManager->createPartner();
        $createCustomerForm = $this->container->get('form.factory')->create(new QuickCustomerType(), $customer, array());

        return $createCustomerForm;
    }

}
