<?php

namespace Vespolina\StoreBundle\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Vespolina\Entity\Partner\Partner;
use Vespolina\Entity\Pricing\Element\TotalDoughValueElement;
use Vespolina\Entity\Pricing\PricingSet;
use Vespolina\StoreBundle\ProcessScenario\Setup\SetupCLIProcess;

class SetupCommand extends ContainerAwareCommand
{

    protected $input;
    protected $country;
    protected $output;
    protected $state;
    protected $type;

    protected function configure()
    {
        $this
            ->setName('vespolina:store-setup')
            ->setDescription('Setup a Vespolina demo store')
            ->addOption('country', null, InputOption::VALUE_OPTIONAL, 'Country', 'US')
            ->addOption('state', null, InputOption::VALUE_OPTIONAL, 'State', '')
            ->addOption('type', null, InputOption::VALUE_OPTIONAL, 'Store type', 'beverages')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $processContext = array();

        //Prepare the context before passing it on to the process steps
        foreach ($input->getOptions() as $key => $value) {
            $processContext[$key] = $value;
        }

        $setupProcess = new SetupCLIProcess($this->getContainer(), $processContext);
        $setupProcess->init();
        $setupProcess->execute();

        die('done');

        //Set up various employees
        $this->setupEmployees($input, $output);


        //Setup on or multiple stores
        $stores = $this->setupStores($input, $output);

        //For each store set up a (default) store zone
        $storeZones = $this->setupStoreZones($stores, $input, $output);

        $output->writeln('Finished setting up stores for country "' . $this->country . '" with type "' . $this->type . '"');
    }


    protected function setupStores($input, $output)
    {

    }




}
