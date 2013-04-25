<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Process;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * This class holds the definition of a process.
 * It contains the list of steps to be executed and in which order this should take place
 *
 * @author Daniel Kucharski <daniel@xerias.be>
 */
class ProcessDefinition
{
    protected $steps;

    public function __construct() {

        $this->steps = array();
    }

    public function addProcessStep($name, $class, $state = '') {

        $config = array();
        $config['name'] = $name;
        $config['class'] = $class;
        $config['state'] = $state;

        $this->steps[] = $config;
    }

    public function getInitialStep() {

        return $this->steps[0];
    }

    public function getStepConfig($name)
    {
       foreach ($this->steps as $stepConfig) {
           if ($stepConfig['name'] == $name) {
               return $stepConfig;
           }
       }
    }

    public function getNextStepConfig($name) {

        $i = 0;
        foreach ($this->steps as $stepConfig) {
            $i++;
            if ($stepConfig['name'] == $name) {

                if ($i+1 <= count($this->steps)) {
                    return $this->steps[$i+1];
                }
            }
        }
    }

    public function getSteps() {

        return $this->steps;
    }
}
