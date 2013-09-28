<?php

/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Document;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ODM\MongoDB\DocumentManager;

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
    protected $dm;
    protected $primaryIdentifier;

    public function __construct(DocumentManager $dm, $storeClass, $storesConfigurations)
    {
        $this->dm = $dm;

        $this->storeClass = $storeClass;
        $this->storeRepo = $this->dm->getRepository($storeClass);

        parent::__construct($storeClass, $storesConfigurations);
    }

    /**
     * @inheritdoc
     */
    public function findStoreByCode($code)
    {
        $store =  $this->storeRepo->findOneByCode($code);

        if (null != $store) {
            $store->prepareLoad();
        }

        return $store;
    }

    /**
     * @inheritdoc
     */
    public function updateStore(StoreInterface $store, $andFlush = true)
    {
        $store->preparePersistence();

        $this->dm->persist($store);
        if ($andFlush) {
            $this->dm->flush();
        }
    }
}
