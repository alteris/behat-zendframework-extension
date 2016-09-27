<?php

namespace Alteris\BehatZendframeworkExtension\ServiceContainer;

use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Behat\Testwork\ServiceContainer\ServiceProcessor;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class Extension
 * @package Alteris\BehatZendframeworkExtension\ServiceContainer
 */
class Extension implements ExtensionInterface
{
    /**
     * Main service name
     */
    const SERVICE_NAME = 'zendframework_extension';

    const APPLICATION_FACTORY_ID = 'zendframework_extension.zend.factory';

    /**
     * Tag for register new factories
     */
    const FACTORY_TAG = 'zendframework_extension.factory_tag';

    /**
     * @var ServiceProcessor
     */
    private $processor;

    /**
     * Initializes compiler pass.
     *
     * @param null|ServiceProcessor $processor
     */
    public function __construct(ServiceProcessor $processor = null)
    {
        $this->processor = $processor ?: new ServiceProcessor();
    }

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        $this->processRegisterApplicationFactory($container);
    }

    /**
     * Returns the extension config key.
     *
     * @return string
     */
    public function getConfigKey()
    {
        return Extension::SERVICE_NAME;
    }

    /**
     * Initializes other extensions.
     *
     * This method is called immediately after all extensions are activated but
     * before any extension `configure()` method is called. This allows extensions
     * to hook into the configuration of other extensions providing such an
     * extension point.
     *
     * @param ExtensionManager $extensionManager
     */
    public function initialize(ExtensionManager $extensionManager)
    {
    }

    /**
     * Setups configuration for the extension.
     *
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('type')->defaultValue('zend_application')->end()
            ->scalarNode('configuration')->info('Path to config path of Zend application')->end()
            ->arrayNode('options')
            ->prototype('variable')
            ->end();
    }

    /**
     * Loads extension services into temporary container.
     *
     * @param ContainerBuilder $container
     * @param array $config
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Config'));
        $loader->load('services.yml');
        $container->setParameter(Extension::SERVICE_NAME . '.selected_factory', $config['type']);
        $container->setParameter(Extension::SERVICE_NAME . '.configuration', $config['configuration']);
    }

    /**
     * Processes all search engines in the container.
     *
     * @param ContainerBuilder $container
     */
    private function processRegisterApplicationFactory(ContainerBuilder $container)
    {
        $references = $this->processor->findAndSortTaggedServices($container, self::FACTORY_TAG);
        $definition = $container->getDefinition(Extension::APPLICATION_FACTORY_ID);
        foreach ($references as $reference) {
            $definition->addMethodCall('registerFactory', array($reference));
        }
    }
}