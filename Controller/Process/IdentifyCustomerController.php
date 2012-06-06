<?php

namespace Vespolina\StoreBundle\Controller\Process;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\ValidatorException;

use Vespolina\PartnerBundle\Form\QuickCustomerType;
use Vespolina\StoreBundle\Form\Type\Process\CustomerQuickCreateFormType;
use Vespolina\StoreBundle\Controller\Process\AbstractProcessStepController;

class IdentifyCustomerController extends AbstractProcessStepController
{

    public function executeAction()
    {

        $createCustomerForm = $this->createCustomerQuickCreateForm();
        $partnerManager = $this->container->get('vespolina.partner_manager');
        $processManager = $this->container->get('vespolina.process_manager');
        $request = $this->container->get('request');

        if ($this->isPostForForm($request, $createCustomerForm)) {

            $createCustomerForm->bindRequest($request);

            $process = $this->processStep->getProcess();

            $customerAddressForm = $createCustomerForm->get('address');
            $customerDetailsForm = $createCustomerForm->get('personalDetails');
            $customerPrimaryContactForm = $createCustomerForm->get('primaryContact');

            if ($createCustomerForm->isValid()) {

                // get address and personal details forms
                // FIXME: seems wrong? Why should we manual set this data?
                $customer = $createCustomerForm->getData();
                $customerAddress = $customerAddressForm->getData();
                $customerDetails = $customerDetailsForm->getData();
                $customerPrimaryContact = $customerPrimaryContactForm->getData();

                $customer->addAddress($customerAddress);
                $customer->setPersonalDetails($customerDetails);
                $customer->setName($partnerManager->generatePartnerName($customerDetails));


                //Save into process context & session
                $process->getContext()->set('customer', $customer);
                $this->container->get('session')->set('customer', $customer);

                $partnerManager->updatePartner($customer);

                //Create FOS user & link partner
                $userManager = $this->container->get('fos_user.user_manager');
                $user = $userManager->createUser();
                $user->setPartner($customer);
                $user->setEmail($customerPrimaryContact->getEmail());
                $userManager->updateUser($user);

                //Signal enclosing process step that we are done here
                $process->completeProcessStep($this->processStep);
                $processManager->updateProcess($process);

                return $process->execute();

            }

        } else {



            // We came here because the checkout process 'identify customer' step could not determine the identity of the customer
            $createCustomerForm = $this->createCustomerQuickCreateForm();

        }

        return $this->render('VespolinaStoreBundle:Process:Step/identifyCustomer.html.twig',
                array('currentProcessStep' => $this->processStep,
                    'createCustomerForm' => $createCustomerForm->createView(),
                    'last_username' => ''));

   }

    protected function createCustomerQuickCreateForm()
    {
        $partnerManager = $this->container->get('vespolina.partner_manager');
        $customer = $partnerManager->createPartner();
        $createCustomerForm = $this->container->get('form.factory')->create(new QuickCustomerType(), $customer);

        return $createCustomerForm;
    }

}
