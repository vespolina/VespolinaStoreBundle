<?php

namespace Vespolina\StoreBundle\Controller\Process;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\ValidatorException;
use Vespolina\PartnerBundle\Form\QuickCustomerType;
use Vespolina\StoreBundle\Form\Type\Process\CustomerQuickCreateFormType;
use Vespolina\StoreBundle\Controller\Process\AbstractProcessStepController;

class IdentifyCustomerController extends AbstractProcessStepController
{

    public function executeAction()
    {
        $customerQuickCreateForm = $this->createCustomerQuickCreateForm();
        $customerLoginForm = $this->createCustomerLoginForm();

        $isCompleted = false;

        $addresses = $customerQuickCreateForm->getData()->getAddresses();
        if ($addresses)
            $customerQuickCreateForm->get('address')->setData($addresses->get(0));

        $partnerManager = $this->container->get('vespolina.partner_manager');
        $processManager = $this->container->get('vespolina.process_manager');
        $request = $this->container->get('request');

        if ($this->isPostForForm($request, $customerQuickCreateForm)) {

            $customerQuickCreateForm->bindRequest($request);
            $customerAddressForm = $customerQuickCreateForm->get('address');
            $customerDetailsForm = $customerQuickCreateForm->get('personalDetails');
            $customerPrimaryContactForm = $customerQuickCreateForm->get('primaryContact');

            if ($customerQuickCreateForm->isValid()) {

                // get address and personal details forms
                // FIXME: seems wrong? Why should we manual set this data?
                $customer = $customerQuickCreateForm->getData();
                $customerAddress = $customerAddressForm->getData();
                $customerDetails = $customerDetailsForm->getData();
                $customerPrimaryContact = $customerPrimaryContactForm->getData();

                if ($customerAddress) {
                    $customer->setAddresses(new ArrayCollection(array($customerAddress)));
                }

                if ($customerDetails) {
                    $customer->setPersonalDetails($customerDetails);
                    $customer->setName($partnerManager->generatePartnerName($customerDetails));
                }

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

                $isCompleted = true;

            }

        } else if ($this->isPostForForm($request, $customerLoginForm)) {

            $customerLoginForm->bindRequest($request);

            if ($customerLoginForm->isValid()) {

                //Todo: clean up and move this quick & dirty code out into a dedicated partner authentication service
                $data = $customerLoginForm->getData();
                $partner = null;
                $username = $data['username'];
                $password = $data['password'];

                $userManager = $this->container->get('fos_user.user_manager');

                $user = $userManager->findUserByUsernameOrEmail($username);

                if (null !== $user) {

                    $encoderService = $this->container->get('security.encoder_factory');
                    $encoder = $encoderService->getEncoder($user);
                    $encodedPass = $encoder->encodePassword($password, $user->getSalt());

                    if ($encodedPass == $user->getPassword()) {

                        $partner = $user->getPartner(); //Get the customer object
                        $this->container->get('session')->set('customer', $partner);
                        $isCompleted = true;
                    } else {

                        $customerLoginForm->addError(new FormError('Invalid credentials'));

                    }
                }
            }
        }

        if ($isCompleted) {

            //Signal enclosing process step that we are done here so the process can move on
            $process = $this->processStep->getProcess();
            $process->completeProcessStep($this->processStep);
            $processManager->updateProcess($process);

            return $process->execute();
        }

        return $this->render('VespolinaStoreBundle:Process:Step/identifyCustomer.html.twig',
                array('currentProcessStep' => $this->processStep,
                    'customerCreateForm' => $customerQuickCreateForm->createView(),
                    'customerLoginForm'  => $customerLoginForm->createView(),
                    'last_username' => ''));

   }

    protected function createCustomerLoginForm()
    {
        $customerLoginForm = $this->container->get('form.factory')->create($this->container->get('vespolina_store.login_customer_type'));

        return $customerLoginForm;
    }

    protected function createCustomerQuickCreateForm()
    {
        $customer = $this->getProcessStep()->getProcess()->getContext()->get('customer');
        if (null === $customer) {
            $partnerManager = $this->container->get('vespolina.partner_manager');
            $customer = $partnerManager->createPartner();
        }
        $customerQuickCreateForm = $this->container->get('form.factory')->create($this->container->get('vespolina.partner.quick_customer_type'), $customer);

        return $customerQuickCreateForm;
    }

}
