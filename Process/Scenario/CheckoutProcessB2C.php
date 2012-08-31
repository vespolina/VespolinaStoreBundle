<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Process\Scenario;

use Vespolina\StoreBundle\Process\AbstractProcess;
use Vespolina\StoreBundle\Process\ProcessStepInterface;


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

    protected $context;

    public function __construct($container, $context = array())
    {
        parent::__construct($container, $context);
    }

    public function completeProcessStep(ProcessStepInterface $processStep){

        switch($processStep->getName()) {
            case 'identify_customer':

                $this->setState('determine_fulfillment');
                break;

            case 'determine_fulfillment':

                $this->setState('select_payment_method');
                break;

            case 'select_payment_method':

                $this->setState('review_checkout');
                break;

            case 'review_checkout':

                $this->setState('execute_payment');
                break;

            case 'execute_payment':

                $this->setState('complete_checkout');
                break;

            case 'complete_checkout':

                $this->setState('finished');
                break;

        }
    }



    public function getCurrentProcessStep()
    {

        //This is a simple case in which a state maps to a process step name, but it could be more dynamic
        if ($this->getState() != 'finished') {

            return $this->getProcessStepByName($this->getState());
        }
    }

    public function getClassMap()
    {
        return array(
            'identify_customer'      => 'Vespolina\StoreBundle\Process\Step\IdentifyCustomer',
            'identify_customer'      => 'Vespolina\StoreBundle\Process\Step\IdentifyCustomer',
            'determine_fulfillment'  => 'Vespolina\StoreBundle\Process\Step\DetermineFulfillment',
            'select_payment_method'  => 'Vespolina\StoreBundle\Process\Step\SelectPaymentMethod',
            'review_checkout'        => 'Vespolina\StoreBundle\Process\Step\ReviewCheckout',
            'execute_payment'        => 'Vespolina\StoreBundle\Process\Step\ExecutePayment',
            'complete_checkout'      => 'Vespolina\StoreBundle\Process\Step\CompleteCheckout',

        );
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
