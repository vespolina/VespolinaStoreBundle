<?php

namespace Vespolina\StoreBundle\Controller\Process;

use Vespolina\StoreBundle\Controller\AbstractController;
use Vespolina\StoreBundle\Process\ProcessStepInterface;

class AbstractProcessStepController extends AbstractController
{
    protected $processStep;

    public function __construct(ProcessStepInterface $processStep)
    {
        $this->processStep = $processStep;
    }

    public function getProcessStep()
    {
        return $this->processStep;
    }
}
