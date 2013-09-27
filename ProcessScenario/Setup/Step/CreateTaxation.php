<?php

namespace Vespolina\StoreBundle\ProcessScenario\Setup\Step;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Vespolina\Entity\Partner\Partner;
use Vespolina\Entity\Pricing\Element\TotalDoughValueElement;

class CreateTaxation extends AbstractSetupStep
{
    public function execute(&$context)
    {
        $defaultTaxRate = 0;
        $fallbackTaxRate = 0;
        $taxationManager = $this->getContainer()->get('vespolina.taxation_manager');
        $taxSchema = $taxationManager->loadTaxSchema($context['country']);
        $taxSchema['defaultTaxRate']  = 0;

        if ($taxSchema['zones']) {

            foreach($taxSchema['zones'] as $taxZone) {
                $taxationManager->updateTaxZone($taxZone, true);

                if ($taxZone->getCountry() == $context['country'] &&
                    $taxZone->getState() ==$context['state']) {

                    $defaultTaxRate = $taxZone->getDefaultRate();
                }

                if (!$fallbackTaxRate) {

                    $fallbackTaxRate = $taxZone->getDefaultRate();
                }
            }

            if (!$defaultTaxRate) {

                $defaultTaxRate = $fallbackTaxRate;
            }

            $taxSchema['defaultTaxRate'] = $defaultTaxRate;
            $context['taxSchema'] = $taxSchema;

            $this->getLogger()->addInfo('Setup ' . count($taxSchema['zones']) . ' tax zone(s) with default tax rate ' . $defaultTaxRate . '%');

        } else {

            $this->getLogger()->addError('No taxation schema exists for country ' . $this->country);
        }

    }

    public function getName()
    {
        return 'create_taxation';
    }
}
