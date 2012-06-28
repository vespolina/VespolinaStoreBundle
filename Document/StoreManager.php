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

use Vespolina\StoreBundle\Document\Store;
use Vespolina\StoreBundle\Model\StoreInterface;
use Vespolina\StoreBundle\Model\StoreManager as BaseStoreManager;
/**
 * @author Daniel Kucharski <daniel@xerias.be>
 * @author Richard Shank <develop@zestic.com>
 */
class StoreManager extends BaseStoreManager
{
    protected $class;
    protected $storeRepo;
    protected $dm;
    protected $primaryIdentifier;

    public function __construct(DocumentManager $dm, $class, $storesConfigurations)
    {
        $this->dm = $dm;

        $this->storeRepo = $this->dm->getRepository($class);
        $metadata = $dm->getClassMetadata($class);
        $this->class = $metadata->name;

        parent::__construct($storesConfigurations);
    }


    /**
     * @inheritdoc
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @inheritdoc
     */
    public function findStoreByCode($code)
    {
        return $this->storeRepo->find($code);
    }

    /**
     * @inheritdoc
     */
    public function updateStore(StoreInterface $store, $andFlush = true)
    {
        $this->dm->persist($store);
        if ($andFlush) {
            $this->dm->flush();
        }
    }
}
