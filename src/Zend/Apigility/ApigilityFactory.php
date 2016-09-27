<?php
namespace Alteris\BehatZendframeworkExtension\Zend;

use ZF\Apigility\Application;

/**
 * Class ApigilityFactory
 * @package Alteris\BehatZendframeworkExtension\Zend
 */
class Factory
{
    /**
     * @var array
     */
    private $parameters;

    /**
     * ApplicationFactory constructor.
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return \Zend\Mvc\ApplicationInterface
     */
    public function factory()
    {
        $appConfigPath = $this->getConfigurationPath();
        if (!defined('APPLICATION_PATH')) {
            define('APPLICATION_PATH', realpath(dirname($appConfigPath) . '/../'));
        }

        $appConfig = include APPLICATION_PATH . '/config/application.config.php';

        if (file_exists(APPLICATION_PATH . '/config/development.config.php')) {
            $appConfig = \Zend\Stdlib\ArrayUtils::merge($appConfig,
                include APPLICATION_PATH . '/config/development.config.php');
        }

        $app = Application::init($appConfig);

        $events = $app->getEventManager();
        $app->getServiceManager()->get('SendResponseListener')->detach($events);

        return $app;
    }

    /**
     * @return array
     */
    private function getConfigurationPath()
    {
        $path = isset($this->parameters['configuration']) ? $this->parameters['configuration'] : '';

        if (!file_exists($path)) {
            throw new \RuntimeException(sprintf("Invalid path to configuration: '%s'", $path));
        }

        return $path;
    }
}