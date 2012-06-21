<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Model;
use Doctrine\Common\Collections\ArrayCollection;
use Vespolina\StoreBundle\Model\StoreInterface;
use Vespolina\StoreBundle\Model\StoreZoneInterface;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
abstract class Store implements StoreInterface
{
    protected $id;
    protected $code;
    protected $displayName;
    protected $defaultCountry;
    protected $defaultState;
    protected $defaultCurrency;
    protected $defaultProductView;
    protected $legalName;
    protected $operationalMode;
    protected $salesChannel;
    protected $storeZones;
    protected $taxationEnabled;

     public function __construct()
    {
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
        return $this->getDisplayName();
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

    /**
     * @inheritdoc
     */
    public function getSalesChannel()
    {
        return $this->salesChannel;
    }

    /**
     * @inheritdoc
     */
    public function setSalesChannel($salesChannel)
    {

        $this->salesChannel = $salesChannel;
    }

    public function setDefaultCurrency($defaultCurrency)
    {
        $this->defaultCurrency = $defaultCurrency;
    }

    public function getDefaultCurrency()
    {
        return $this->defaultCurrency;
    }

    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    public function getDisplayName()
    {
        return $this->displayName;
    }

    public function setLegalName($legalName)
    {
        $this->legalName = $legalName;
    }

    public function getLegalName()
    {
        return $this->legalName;
    }

    public function setOperationalMode($operationalMode)
    {
        $this->operationalMode = $operationalMode;
    }

    public function getOperationalMode()
    {
        return $this->operationalMode;
    }

    public function setDefaultProductView($defaultProductView)
    {
        $this->defaultProductView = $defaultProductView;
    }

    public function getDefaultProductView()
    {
        return $this->defaultProductView;
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

    public function setDefaultCountry($defaultCountry)
    {
        $this->defaultCountry = $defaultCountry;
    }

    public function getDefaultCountry()
    {
        return $this->defaultCountry;
    }

    public function setDefaultState($defaultState)
    {
        $this->defaultState = $defaultState;
    }

    public function getDefaultState()
    {
        return $this->defaultState;
    }

    public function setTaxationEnabled($taxationEnabled)
    {
        $this->taxationEnabled = $taxationEnabled;
    }

    public function getTaxationEnabled()
    {
        return $this->taxationEnabled;
    }


}
