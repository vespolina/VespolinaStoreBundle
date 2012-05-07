<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Process;

use Vespolina\StoreBundle\Process\AbstractProcess;

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

    public function __construct($container)
    {
        parent::__construct($container);
        $this->context = array('state' => 'initial');
    }

    public function completeProcessStep(ProcessStepInterface $processStep){

        switch($processStep->getName()) {

            case 'identify_customer':
                $this->context['state'] = 'customer_identified';
                break;
        }
    }

    public function execute()
    {

        $currentProcessStep = $this->getCurrentProcessStep();
        return $currentProcessStep->execute($this);
    }

    public function getCurrentProcessStep()
    {
        switch($this->context['state']) {

            case 'initial':

                //Execute step 1
                return $this->getProcessStepByName('identify_customer');

            case 'customer_identified':

                //Execute step 2
                return $this->getProcessStepByName('determine_fulfillment');

        }
    }

    public function getClassMap()
    {
        return array(
            'identify_customer'      => 'Vespolina\StoreBundle\Process\Step\IdentifyCustomer',
            'determine_fulfillment'  => 'Vespolina\StoreBundle\Process\Step\DetermineFulfillment',
        );
    }

    public function getName()
    {
        return 'checkout_b2c';
    }

}