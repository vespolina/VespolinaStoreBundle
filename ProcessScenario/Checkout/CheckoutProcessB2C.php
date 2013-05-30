<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\ProcessScenario\Checkout;

use Vespolina\StoreBundle\Process\AbstractProcess;
use Vespolina\StoreBundle\Process\ProcessStepInterface;
use Vespolina\StoreBundle\Process\ProcessDefinition;

/**
 * This process models a commonly used checkout process which consists of following steps:
 *
 * 1)identifying / register the customer
 * 2)determine fulfillment (eg. shipment) type
 * 3)choose payment type
 * 4)review
 * 5)pay
 * 6)send a confirmation of the order by mail
 *
 * @author Daniel Kucharski <daniel@xerias.be>
 */
class CheckoutProcessB2C extends AbstractProcess
{
    public function build() {

        $definition = new ProcessDefinition();
        $definition->addProcessStep('identify_customer',
                                    'Vespolina\StoreBundle\ProcessScenario\Checkout\Step\IdentifyCustomer');
        $definition->addProcessStep('determine_fulfillment',
                                    'Vespolina\StoreBundle\ProcessScenario\Checkout\Step\DetermineFulfillment');
        $definition->addProcessStep('select_payment_method',
                                    'Vespolina\StoreBundle\ProcessScenario\Checkout\Step\SelectPaymentMethod');
        $definition->addProcessStep('review_checkout',
                                    'Vespolina\StoreBundle\ProcessScenario\Checkout\Step\ReviewCheckout');
        $definition->addProcessStep('execute_payment',
                                    'Vespolina\StoreBundle\ProcessScenario\Checkout\Step\ExecutePayment');
        $definition->addProcessStep('complete_checkout',
                                    'Vespolina\StoreBundle\ProcessScenario\Checkout\Step\CompleteCheckout');

        return $definition;
    }
    public function completeProcessStep(ProcessStepInterface $processStep)
    {
        $nextStepConfig = $this->definition->getNextStepConfig($processStep->getName());

        //Detect if this process step is followed by another process step
        if (null != $nextStepConfig) {
            $this->setState($nextStepConfig['name']);
        } else {
            $this->setState('completed');
        }
    }

    public function getCurrentProcessStep()
    {
        //This is a simple case in which a state maps to a process step name, but it could be more dynamic
        if (!$this->isCompleted()) {

            return $this->getProcessStepByName($this->getState());
        }
    }

    public function getInitialState()
    {
        return 'identify_customer';
    }

    public function getName()
    {
        return 'checkout_b2c';
    }

}
