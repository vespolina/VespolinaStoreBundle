<?php

namespace Vespolina\StoreBundle\Tests;
use Vespolina\StoreBundle\Model\Store;

class TestStore extends Store
{
    public function setId ($id)
    {
        $this->id = $id;
    }
}
