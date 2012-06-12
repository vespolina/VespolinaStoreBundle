<?php
/**
 * (c) 2011-2012 Vespolina Project http://www.vespolina-project.org
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
class SelectPaymentMethodFormType extends AbstractType
{
    protected $paymentMethodChoices;

    public function __construct($paymentMethodChoices)
    {
        $this->paymentMethodChoices = $paymentMethodChoices;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('payment_method', 'choice', array(
            'choices'   => $this->paymentMethodChoices,
            'expanded'  => true,
            'multiple'  => false,
        ));

    }

    public function getName()
    {
        return 'vespolina_store_select_payment_method';
    }

}
