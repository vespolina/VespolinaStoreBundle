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
use Vespolina\StoreBundle\Model\StoreZoneInterface;
use Vespolina\StoreBundle\Model\StoreZoneManager as BaseStoreZoneManager;
/**
 * @author Daniel Kucharski <daniel@xerias.be>
 * @author Richard Shank <develop@zestic.com>
 */
class StoreZoneManager extends BaseStoreZoneManager
{
    protected $storeZoneClass;
    protected $storeZoneRepo;
    protected $dm;

    public function __construct(DocumentManager $dm, $storeZoneClass)
    {
        $this->dm = $dm;

        $this->storeZoneClass = $storeZoneClass;
        $this->storeZoneRepo = $this->dm->getRepository($storeZoneClass);

        parent::__construct($storeZoneClass);
    }

    /**
     * @inheritdoc
     */
    public function findStoreById($id)
    {
        return $this->storeRepo->find($id);
    }

    /**
     * @inheritdoc
     */
    public function updateStoreZone(StoreZoneInterface $storeZone, $andFlush = true)
    {
        $this->dm->persist($storeZone);
        if ($andFlush) {
            $this->dm->flush();
        }
    }
}
