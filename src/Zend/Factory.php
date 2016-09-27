<?php
namespace Alteris\BehatZendframeworkExtension\Zend;

use Alteris\BehatZendframeworkExtension\Exception\ZendframeworkExtensionException;
use Zend\Mvc\ApplicationInterface;

class Factory
{
    /**
     * @var \ArrayObject
     */
    private $collection;

    /**
     * @var string
     */
    private $selectedAlias;

    /**
     * Factory constructor.
     * @param string $selectedAlias
     */
    public function __construct($selectedAlias)
    {
        $this->collection = new \ArrayObject();
        $this->selectedAlias = $selectedAlias;
    }

    /**
     * @param ApplicationFactoryInterface $factory
     */
    public function registerFactory(ApplicationFactoryInterface $factory)
    {
        $this->collection->offsetSet($factory->getName(), $factory);
    }

    /**
     * @return ApplicationInterface
     * @throws ZendframeworkExtensionException
     */
    public function factory()
    {
        if (!$this->collection->offsetExists($this->selectedAlias)){
            throw new ZendframeworkExtensionException(sprintf("Factory for alias '%s' don't exist", $this->selectedAlias));
        }
        /** @var ApplicationFactoryInterface $factory */
        $factory = $this->collection->offsetGet($this->selectedAlias);
        return $factory->factory();
    }
}