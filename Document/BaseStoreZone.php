<?php

/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Document;

use Vespolina\StoreBundle\Model\StoreZone as AbstractStoreZone;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
abstract class BaseStoreZone extends AbstractStoreZone
{
    protected $id;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
}