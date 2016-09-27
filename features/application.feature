Feature: Application connector to Behat
  In order to use context
  As a feature automator
  I need to be able to use behat context initializer interface

  Background: Create module for tests
    Given there is a file named "app/config/application.config.php" with:
    """
    <?php
    $config = array(
        'modules' => array(
            'ModuleTest'
          ),
        'module_listener_options' => array(
            'module_paths' => array(
                __DIR__.'/../modules',
                './vendor',
            ),
            'config_glob_paths' => array(
                'config/autoload/{,*.}{global,local}.php',
            ),
        ),
    );
    return $config;
    """
    And there is a file named "app/modules/ModuleTest/config/module.config.php" with:
    """
    <?php
    namespace ModuleTest;

    return array(
    );
    """
    And there is a file named "app/modules/ModuleTest/Module.php" with:
    """
    <?php
    namespace ModuleTest;

    class Module
    {
        public function getConfig()
        {
            return include __DIR__ . '/config/module.config.php';
        }

        public function getAutoloaderConfig()
        {
            return array(
                'Zend\Loader\StandardAutoloader' => array(
                    'namespaces' => array(
                        __NAMESPACE__ => __DIR__ . '/src/',
                    ),
                ),
            );
        }
    }
    """
    And a file named "behat.yml" with:
    """
    default:
      autoload:
        '': %paths.base%/features/bootstrap/
      suites:
        default:
          path: %paths.base%/features
      extensions:
        Alteris\BehatZendframeworkExtension\ServiceContainer\Extension:
          configuration: app/config/application.config.php
      """

  Scenario: Tests initializer
    Given a file named "features/bootstrap/FeatureContext.php" with:
    """
      <?php

      use Behat\Behat\Context\Context;
      use Alteris\BehatZendframeworkExtension\Context\ContextAwareInterface;

      class FeatureContext implements Context, ContextAwareInterface
      {
          protected $app;

          public function setApplication(\Zend\Mvc\ApplicationInterface $application)
          {
              $this->app = $application;
          }

          /**
           * @Given context hold correct adapter
           */
          public function contextHoldCorrectAdapter()
          {
              if (!($this->app instanceof \Zend\Mvc\ApplicationInterface)) {
                  throw new \Exception("Context don't hold correct adapter");
              }
          }

          /**
           * @Given context hold correct application
           */
          public function contextHoldCorrectApplication()
          {
              if (!($this->app->getServiceManager() instanceof \Zend\ServiceManager\ServiceManager)) {
                  throw new \Exception("Context don't hold correct application");
              }
          }
      }
      """
    And a file named "features/feature.feature" with:
    """
      Feature:
        Scenario:
          Given context hold correct adapter
          And context hold correct application
      """
    When I run "behat -f progress --no-colors --append-snippets"
    Then it should pass with:
    """
    .

    1 scenario (1 passed)
    2 steps (2 passed)
    """


  Scenario: Test service
    Given there is a file named "app/modules/ModuleTest/config/module.config.php" with:
    """
    <?php
    namespace ModuleTest;

    return array(
      'service_manager' => array(
         'invokables' => array(
             'testService' => '\ModuleTest\Service\TestService'
         )
      ),
    );
    """
    And there is a file named "app/modules/ModuleTest/src/Service/TestService.php" with:
    """
    <?php
    namespace ModuleTest\Service;

    class TestService
    {
      public function printHello()
      {
        return 'hello';
      }
    }
    """
    And a file named "features/bootstrap/FeatureContext.php" with:
    """
      <?php

      use Behat\Behat\Context\Context;
      use Alteris\BehatZendframeworkExtension\Context\ContextAwareInterface;

      class FeatureContext implements Context, ContextAwareInterface
      {
          protected $app;

          public function setApplication(\Zend\Mvc\ApplicationInterface $application)
          {
              $this->app = $application;
          }

          /**
           * @Then it could be possible to call a service using :serviceName as alias of :serviceClass
           */
          public function itCouldBePossibleToCallAServiceUsingAsParam($serviceName, $serviceClass)
          {
              $module = $this->app->getServiceManager()->get($serviceName);
              if (!$module instanceOf $serviceClass) {
                  throw new \Exception("Service manager can't call correct service");
              }
          }
      }
      """
    And a file named "features/feature.feature" with:
    """
      Feature:
        Scenario:
          Given it could be possible to call a service using "testService" as alias of "ModuleTest\Service\TestService"
      """
    When I run "behat -f progress --no-colors --append-snippets"
    Then it should pass with:
    """
    .

    1 scenario (1 passed)
    1 step (1 passed)
    """