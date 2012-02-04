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
    protected $name;
    protected $salesChannel;



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
        return $this->name;
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
    public function setName($name)
    {

        $this->name = $name;
    }

    /**
     * @inheritdoc
     */
    public function setSalesChannel($salesChannel)
    {

        $this->salesChannel = $salesChannel;
    }
}