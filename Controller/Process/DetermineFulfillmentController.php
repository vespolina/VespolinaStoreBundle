<?php

namespace Vespolina\StoreBundle\Controller\Process;

use Vespolina\StoreBundle\Controller\Process\AbstractProcessStepController;

class DetermineFulfillmentController extends AbstractProcessStepController
{
    public function executeAction()
    {
        // We came here because the checkout process 'identify customer' step could not determine the identity of the customer
        return $this->render('VespolinaStoreBundle:Process:Step/determineFulfillment.html.twig', array('currentProcessStep' => $this->processStep));
    }

}
