<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Vespolina\StoreBundle\Process;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
interface ProcessStepInterface
{

    function init($firstTime = false);
    function execute($context);
    function getDisplayName();

}
