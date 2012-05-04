<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Process;

use Vespolina\StoreBundle\Process\ProcessInterface;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
abstract class AbstractProcessStep implements ProcessStepInterface
{
    protected $process;

    public function __construct(ProcessInterface $process)
    {
        $this->process = $process;
    }

    protected function getController($class)
    {
        $controller = new $class($this);

        return $controller;
    }

}