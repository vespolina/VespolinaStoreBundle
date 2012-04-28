<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Model;

use Vespolina\StoreBundle\Model\StoreInterface;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
abstract class Store implements StoreInterface
{
    protected $id;
    protected $defaultCurrency;
    protected $displayName;
    protected $legalName;
    protected $operationalMode;
    protected $salesChannel;
    protected $defaultProductView;

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


}