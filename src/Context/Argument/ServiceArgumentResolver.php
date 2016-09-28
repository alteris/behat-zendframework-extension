<?php
namespace Alteris\BehatZendframeworkExtension\Context\Argument;

use Behat\Behat\Context\Argument\ArgumentResolver;
use ReflectionClass;
use Zend\Mvc\ApplicationInterface;

class ServiceArgumentResolver implements ArgumentResolver
{
    /** @var  ApplicationInterface */
    private $application;

    /**
     * ServiceArgumentResolver constructor.
     * @param ApplicationInterface $application
     */
    public function __construct(ApplicationInterface $application)
    {
        $this->application = $application;
    }

    /**
     * Resolves context constructor arguments.
     *
     * @param ReflectionClass $classReflection
     * @param mixed[] $arguments
     *
     * @return mixed[]
     */
    public function resolveArguments(ReflectionClass $classReflection, array $arguments)
    {
        $newArguments = array();
        foreach ($arguments as $key => $argument) {
            $newArguments[$key] = $this->resolveArgument($argument);
        }
        return $newArguments;
    }

    private function resolveArgument($argument)
    {
        if (is_array($argument)) {
            return array_map(array($this, 'resolveArgument'), $argument);
        }

        if (!is_string($argument)) {
            return $argument;
        }

        $serviceManager = $this->application->getServiceManager();

        if ($service = $this->getService($serviceManager, $argument)) {
            return $service;
        }
        return $argument;
    }

    /**
     * @param $serviceManager
     * @param $argument
     * @return mixed
     */
    private function getService($serviceManager, $argument)
    {
        $serviceName = $this->getServiceName($argument);
        if ($serviceManager->has($serviceName)) {
            return $serviceManager->get($serviceName);
        }
        return null;
    }

    /**
     * @param string $argument
     * @return string|null
     */
    private function getServiceName($argument)
    {
        if (preg_match('/^@[?]?([^@].*)$/', $argument, $matches)) {
            return $matches[1];
        }
        return null;
    }
}