<?php
/**
 * (c) 2011 - ∞ Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Form\Type\Process;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
/**
 * Quickly create a customer
 *
 * @author Daniel Kucharski <daniel@xerias.be>
 */
class SelectFulfillmentMethodFormType extends AbstractType
{
    protected $fulfillmentChoices;

    public function __construct($fulfillmentChoices)
    {
        $this->fulfillmentChoices = $fulfillmentChoices;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fulfillment_method', 'choice', array(
            'choices'   => $this->fulfillmentChoices,
            'expanded'  => true,
            'multiple'  => false,
        ));

    }

    public function getName()
    {
        return 'vespolina_store_select_fulfilment_method';
    }

    public function setFulfillmentChoices($fulfillmentChoices)
    {
        $this->fulfillmentChoices = $fulfillmentChoices;
    }
}
