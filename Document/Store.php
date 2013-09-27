<?php

/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Document;

use Vespolina\StoreBundle\Model\Settings;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
class Store extends BaseStore
{
    public function preparePersistence()
    {
        $this->parameters = $this->settings->getParameters();
    }

    public function prepareLoad()
    {
        if (null == $this->settings) $this->settings = new Settings();
        $this->settings->setParameters($this->parameters);
    }
}