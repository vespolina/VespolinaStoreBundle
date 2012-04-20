<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Model;

use Vespolina\StoreBundle\Model\StoreZoneInterface;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
class StoreZone implements StoreZoneInterface
{
    protected $name;
    protected $taxonomyName;
    /**
     * Name of this zone
     *
     * @return mixed
     */
    function getName()
    {
        return $this->name;
    }

    /**
     * Name of the taxonomy associated with this zone
     *
     * @return mixed
     */
    function getTaxonomyName()
    {
        return $this->taxonomyName;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setTaxonomyName($taxonomyName)
    {
        $this->taxonomyName = $taxonomyName;
    }
}