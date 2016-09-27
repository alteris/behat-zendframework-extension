<?php
namespace Alteris\BehatZendframeworkExtension\Zend;

use Zend\Mvc\ApplicationInterface;

interface ApplicationFactoryInterface
{
    /**
     * @return ApplicationInterface
     */
    public function factory();

    /**
     * @return string
     */
    public function getName();
}