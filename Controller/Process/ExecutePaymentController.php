<?php

namespace Vespolina\StoreBundle\Controller\Process;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Vespolina\StoreBundle\Form\Type\Process\CustomerQuickCreate;
use Vespolina\StoreBundle\Controller\Process\AbstractProcessStepController;

class ExecutePaymentController extends AbstractProcessStepController
{
    public function executeAction()
    {

        return $this->render('VespolinaStoreBundle:Process:Step/executePayment.html.twig',
                    array('currentProcessStep' => $this->processStep));
    }


}
