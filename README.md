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

   .. code-block:: yaml

        default:
            # ...
            extensions:
                Alteris\BehatZendframeworkExtension\ServiceContainer\Extension:
                    configuration: PATH_TO_application.config.php
                    
## Injecting Services

The extension will automatically convert parameters injected into a context that
start with '@' into services:

.. code-block:: yaml

    default:
        suites:
            default:
                contexts:
                    - FeatureContext:
                        testService: '@testService'
            extensions:
                Alteris\BehatZendframeworkExtension\ServiceContainer\Extension:
                    configuration: PATH_TO_application.config.php

The FeatureContext will then be initialized with the Symfony2 session from the container:

.. code-block:: php

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
    
## Versioning

Staring version ``1.0.0``, will follow [Semantic Versioning v2.0.0](http://semver.org/spec/v2.0.0.html).

## Contributors

* [Tomasz Kunicki](https://github.com/timiTao) 