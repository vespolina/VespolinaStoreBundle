<?php

namespace Vespolina\StoreBundle\Tests\DependencyInjection;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vespolina\StoreBundle\DependencyInjection\VespolinaStoreExtension;
use Symfony\Component\Yaml\Parser;

class VespolinaStoreExtensionTest extends \PHPUnit_Framework_TestCase
{
    protected $configuration;
    private $definitions = array(
        'vespolina.store.listener.request',
        'vespolina.store.listener.request',
        'vespolina.store_manager',
        'vespolina.store.store_zone_manager',
        'vespolina.store_zone_manager'
    );
    public function testStoreLoadConfigPass()
    {
        $loader = new VespolinaStoreExtension();
        $config = $this->getBaseConfig();
        $ret = $loader->load(array ($config), new ContainerBuilder());
        $this->assertEquals($ret, null);
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testStoreLoadThrowsExceptionUnlessDatabaseDriverSet()
    {
        $loader = new VespolinaStoreExtension();
        $config = $this->getBaseConfig();
        unset($config['db_driver']);
        $loader->load(array ($config), new ContainerBuilder());
    }

    public function testStoreLoadServicesWithDefaults()
    {
        $this->createDefaultConfiguration();

        foreach ($this->definitions as $definition)
        {
            $this->assertHasDefinition($definition);
        }
    }

    public function testStoreLoadServicesWithOrm()
    {
        $this->createOrmConfiguration();

        foreach ($this->definitions as $definition)
        {
            $this->assertHasDefinition($definition);
        }
    }

    public function testStoreLoadParameterWithDefaults()
    {
        $this->createDefaultConfiguration();

        $this->assertParameter('Vespolina\StoreBundle\Document\StoreManager', 'vespolina.store.store_manager.class');
        $this->assertParameter('Vespolina\StoreBundle\Document\StoreZoneManager', 'vespolina.store.store_zone_manager.class');
        $this->assertParameter('Vespolina\StoreBundle\Document\Store', 'vespolina.store.model.store.class');
        $this->assertParameter('Vespolina\StoreBundle\Document\StoreZone', 'vespolina.store.model.store_zone.class');
    }

    public function testStoreLoadParameterWithOrm()
    {
        $this->createOrmConfiguration();

        $this->assertParameter('Vespolina\StoreBundle\Entity\StoreManager', 'vespolina.store.store_manager.class');
        $this->assertParameter('Vespolina\StoreBundle\Entity\StoreZoneManager', 'vespolina.store.store_zone_manager.class');
        $this->assertParameter('Vespolina\StoreBundle\Entity\Store', 'vespolina.store.model.store.class');
        $this->assertParameter('Vespolina\StoreBundle\Entity\StoreZone', 'vespolina.store.model.store_zone.class');
    }

    public function testStoreLoadParameterStoresConfigurations()
    {
        $this->createDefaultConfiguration();
        $config = $this->getBaseConfig();

        unset($config['stores']['default_store']['id']);
        $this->assertEquals( $config['stores'], $this->configuration->getParameter('vespolina.store.stores_configurations'));

    }

    private function createDefaultConfiguration()
    {
        $this->configuration = new ContainerBuilder();
        $loader = new VespolinaStoreExtension();
        $config = $this->getBaseConfig();
        $loader->load(array($config), $this->configuration);
        $this->assertTrue($this->configuration instanceof ContainerBuilder);
    }

    private function createOrmConfiguration()
    {
        $this->configuration = new ContainerBuilder();
        $loader = new VespolinaStoreExtension();
        $config = $this->getBaseConfig();
        $config['db_driver'] = 'orm';
        $loader->load(array($config), $this->configuration);
        $this->assertTrue($this->configuration instanceof ContainerBuilder);
    }

    private function getBaseConfig()
    {
        $yaml = <<<EOF
db_driver: mongodb
stores:
    default_store:
        id: default_store
        default: true
        display_name: Test Store
        legal_name: My Test Store
        sales_channel: default_store_web
        operational_mode: standard
        default_product_view: tiled
EOF;

        $parser = new Parser();
        return $parser->parse($yaml);
    }

    private function assertHasDefinition($id)
    {
        $this->assertTrue(($this->configuration->hasDefinition($id) ?: $this->configuration->hasAlias($id)));
    }

    private function assertParameter($value, $key)
    {
        $this->assertEquals($value, $this->configuration->getParameter($key), sprintf('%s parameter is correct', $key));
    }
}
