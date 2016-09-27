<?php

namespace Alteris\BehatZendframeworkExtension\Context\Initializer;

use Alteris\BehatZendframeworkExtension\Context\ContextAwareInterface;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer as BehatContextInitializer;
use Zend\Mvc\ApplicationInterface;

/**
 * Class ContextInitializer
 * @package Alteris\BehatZendframeworkExtension\Context\Initializer
 */
class ContextInitializer implements BehatContextInitializer
{
    /**
     * @var ApplicationInterface
     */
    private $application;

    /**
     * ContextInitializer constructor.
     * @param ApplicationInterface $application
     */
    public function __construct(ApplicationInterface $application)
    {
        $this->application = $application;
    }


    /**
     * Initializes provided context.
     *
     * @param Context $context
     */
    public function initializeContext(Context $context)
    {
        if ($context instanceof ContextAwareInterface) {
            $context->setApplication($this->application);
        }
    }
}
