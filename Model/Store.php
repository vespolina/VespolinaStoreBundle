<?php

/**
 * (c) 2011 - ∞ Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Vespolina\Entity\Channel\WebStore;
use Vespolina\StoreBundle\Model\StoreInterface;
use Vespolina\StoreBundle\Model\StoreZoneInterface;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
abstract class Store extends WebStore implements StoreInterface
{
    protected $id;
    protected $code;
    protected $name;
    protected $settings;
    protected $storeZones;
    protected $parameters;

    public function __construct()
    {
        $this->settings = new Settings();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {

    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setCode ($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function getSetting($name, $default  = '')
    {
        if ($this->settings->has($name)) {

            return $this->settings->get($name);
        }

        return $default;
    }

    public function setSettings(Settings $settings)
    {
        $this->settings = $settings;
    }

    public function addStoreZone(StoreZoneInterface $storeZone)
    {
        if (!$this->storeZones) {
            $this->storeZones = new ArrayCollection();
            $this->storeZones->add($storeZone);
        }
    }

    public function setStoreZones($storeZones)
    {
        $this->storeZones = $storeZones;
    }

    public function getStoreZones()
    {
        return $this->storeZones;
    }
}
