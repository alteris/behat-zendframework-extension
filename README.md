Behat Zend framework integration
================================

Integration [Behat](http://behat.org/en/latest/) ^3.1 with ``Zend\Mvc\ApplicationInterface``.
This should allow integrate with Zend framework v2.* and v3.*.

## Installing extension

The easiest way to install is by using [Composer](https://getcomposer.org):

```bash
$> curl -sS https://getcomposer.org/installer | php
$> php composer.phar require alteris/behat-zendframework-extension='~1.0'
```

or composer.json

    "require": {
        "alteris/behat-zendframework-extension": "~1.0"
    },

## Configuration

You can then activate the extension in your ``behat.yml``:

        default:
            # ...
            extensions:
                Alteris\BehatZendframeworkExtension\ServiceContainer\Extension:
                    configuration: PATH_TO_application.config.php
                    
## Injecting Application

Your context need to implement ``Alteris\BehatZendframeworkExtension\Context\ContextAwareInterface`` and will be intialized with ``Zend\Mcv\ApplicationInterface``;
                    
## Injecting Services

The extension will automatically convert parameters injected into a context. You need to have set alias/name starting with '@':

    default:
        suites:
            default:
                contexts:
                    - FeatureContext:
                        testService: '@testService'
            extensions:
                Alteris\BehatZendframeworkExtension\ServiceContainer\Extension:
                    configuration: PATH_TO_application.config.php

The FeatureContext will then be initialized with the service from the Zend Framework container:

    <?php
    
    namespace FeatureContext;
    
    use Behat\Behat\Context\Context;
    use ModuleTest\Service\TestService;
    
    class FeatureContext implements Context
    {
        public function __construct(TestService $testService)
        {
            //Service from Zend\Mvc\Application
        }
    }
    
## Extra

* Available integration with [Apigility](https://apigility.org/) with [Alteris\BehatApigilityExtension](https://github.com/alteris/behat-apigility-extension) 

## Examples

* [Application](https://github.com/alteris/behat-zendframework-extension/blob/master/features/application.feature)
* [Argument Resolver](https://github.com/alteris/behat-zendframework-extension/blob/master/features/argument_resolver.feature)
    
## Versioning

Staring version ``1.0.0``, will follow [Semantic Versioning v2.0.0](http://semver.org/spec/v2.0.0.html).

## Contributors

* [Tomasz Kunicki](https://github.com/timiTao) 