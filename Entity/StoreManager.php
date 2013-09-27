<?php

/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Entity;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;

use Vespolina\StoreBundle\Model\StoreInterface;
use Vespolina\StoreBundle\Model\StoreManager as BaseStoreManager;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 * @author Richard Shank <develop@zestic.com>
 */
class StoreManager extends BaseStoreManager
{
    protected $storeClass;
    protected $storeRepo;
    protected $em;
    protected $primaryIdentifier;

    public function __construct(EntityManager $em, $storeClass, $storesConfigurations)
    {
        $this->em = $em;

        $this->storeClass = $storeClass;
        $this->storeRepo = $this->em->getRepository($storeClass);

        parent::__construct($storeClass, $storesConfigurations);
    }

    /**
     * @inheritdoc
     */
    public function findStoreByCode($code)
    {
        return $this->storeRepo->findOneByCode($code);
    }

    /**
     * @inheritdoc
     */
    public function updateStore(StoreInterface $store, $andFlush = true)
    {
        $this->em->persist($store);
        if ($andFlush) {
            $this->em->flush();
        }
    }
}
