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

use Vespolina\StoreBundle\Entity\Store;
use Vespolina\StoreBundle\Model\StoreInterface;
use Vespolina\StoreBundle\Model\StoreManager as BaseStoreManager;
/**
 * @author Daniel Kucharski <daniel@xerias.be>
 * @author Richard Shank <develop@zestic.com>
 */
class StoreManager extends BaseStoreManager
{
    protected $storeRepo;
    protected $em;
    protected $class;
    protected $primaryIdentifier;

    public function __construct(EntityManager $em, $class, $storesConfigurations)
    {
        $this->em = $em;

        $this->storeRepo = $this->em->getRepository($class);
        $metadata = $em->getClassMetadata($class);
        $this->class = $metadata->name;

        parent::__construct($storesConfigurations);
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
