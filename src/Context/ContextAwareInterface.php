<?php
namespace Alteris\BehatZendframeworkExtension\Context;

use Zend\Mvc\ApplicationInterface;


/**
 * Interface ContextAwareInterface
 * @package Alteris\BehatZendframeworkExtension\Context
 */
interface ContextAwareInterface
{
    /**
     * @param ApplicationInterface $application
     * @return void
     */
    public function setApplication(ApplicationInterface $application);
}
