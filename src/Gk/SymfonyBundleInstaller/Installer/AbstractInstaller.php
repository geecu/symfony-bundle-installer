<?php


namespace Gk\SymfonyBundleInstaller\Installer;


abstract class AbstractInstaller
{
    /**
     * @var \ReflectionClass
     */
    protected $reflectionClass;

    /**
     * @return array of command names to be executed in order
     */
    abstract public function getSteps();

    public function getDirectory()
    {
        return dirname($this->getReflectionClass()->getFileName());
    }

    public function getNamespace()
    {
        return __NAMESPACE__;
    }

    protected function getReflectionClass()
    {
        if ($this->reflectionClass) {
            return $this->reflectionClass;
        }

        $this->reflectionClass = new \ReflectionClass($this);

        return $this->reflectionClass;
    }
}
