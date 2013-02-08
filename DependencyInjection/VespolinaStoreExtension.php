<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * (c) Daniel Kucharski <daniel@xerias.be>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

class VespolinaStoreExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        if (!in_array(strtolower($config['db_driver']), array('mongodb', 'orm'))) {
            throw new \InvalidArgumentException(sprintf('Invalid db driver "%s".', $config['db_driver']));
        }
        $loader->load(sprintf('%s.xml', $config['db_driver']));
        $loader->load(sprintf('store.xml'));

        if (isset($config['stores'])) {
            $this->configureStores($config['stores'], $container);
        }

        $definition = new Definition('Vespolina\StoreBundle\Twig\StoreTwigExtension');
        $definition->addTag('twig.extension');
        $container->setDefinition('store_twig_extension', $definition);
    }

    public function getNamespace()
    {
        return 'http://www.vespolina-org/schema/dic/vespolina-store-v1';
    }

    protected function configureStores(array $config, ContainerBuilder $container)
    {
        $container->setParameter('vespolina_store.stores_configurations', $config);
    }
}