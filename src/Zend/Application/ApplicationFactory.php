<?php
namespace Alteris\BehatZendframeworkExtension\Zend\Application;

use Alteris\BehatZendframeworkExtension\Zend\ApplicationFactoryInterface;
use Zend\Mvc\Application;
use Zend\Mvc\ApplicationInterface;

/**
 * Class ApplicationFactory
 * @package Alteris\BehatZendframeworkExtension\Zend
 */
class ApplicationFactory implements ApplicationFactoryInterface
{
    const NAME = 'zend_application';

    /**
     * @var string
     */
    private $configurationPath;

    /**
     * ApplicationFactory constructor.
     * @param string $configurationPath
     */
    public function __construct($configurationPath)
    {
        $this->configurationPath = $configurationPath;
    }

    /**
     * @return ApplicationInterface
     */
    public function factory()
    {
        $app = Application::init($this->getConfiguration());

        return $app;
    }

    /**
     * @return array
     */
    private function getConfiguration()
    {
        $path = isset($this->configurationPath) ? $this->configurationPath : '';
        if (!file_exists($path)) {
            throw new \RuntimeException(sprintf("Invalid path to configuration: '%s'", $path));
        }

        return require_once $path;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }
}