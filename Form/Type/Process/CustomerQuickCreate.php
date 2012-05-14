<?php
/**
 * (c) 2011-2012 Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Form\Type\Process;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
/**
 * Quickly create a customer
 *
 * @author Daniel Kucharski <daniel@xerias.be>
 */
class CustomerQuickCreate extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('name');

    }

    public function getName()
    {
        return 'customer_quick_create';
    }
}