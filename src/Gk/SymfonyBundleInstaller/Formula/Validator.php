<?php

namespace Gk\SymfonyBundleInstaller\Formula;

class Validator
{
    /**
     * @var Formula
     */
    protected $formula;

    /**
     * @var array
     */
    protected $data;

    public function __construct(Formula $formula)
    {
        $this->formula = $formula;

        $this->data = $this->formula->getJsonData();
    }

    public function validate()
    {
        $this->validateData();
        $this->validateAutoloader();
    }

    /**
     * Checks if the formula has an autoloader (created by composer)
     */
    protected function validateAutoloader()
    {
        $autoloaderPath = $this->formula->getAutoloaderPath();

        if (!file_exists($autoloaderPath)) {
            throw new \RuntimeException(sprintf("Formula doesn't have an autoloader file at '%s'. Run 'composer dump-autoload' inside formula's directory",
                $autoloaderPath));
        }
    }

    /**
     * Checks if the formula's data has the required keys
     */
    protected function validateData()
    {
        if (!isset($this->data['type'])) {
            throw new \UnexpectedValueException(sprintf("Package's composer.json doesn't seem to have a 'type' key, expecting type '%s'",
                Formula::JSON_TYPE));
        }

        if ($this->data['type'] != Formula::JSON_TYPE) {
            throw new \UnexpectedValueException(sprintf("Package's composer.json doesn't seem to have a valid type, expecting '%s', got '%s'",
                Formula::JSON_TYPE,
                $this->data['type']));
        }

        if (!isset($this->data['extra'])) {
            throw new \UnexpectedValueException(sprintf("Package's composer.json doesn't have an 'extra' key"));
        }

        if (!isset($this->data['extra'][Formula::BUNDLES_TO_INSTALL_KEY])) {
            throw new \UnexpectedValueException(sprintf("Package's composer.json doesn't specify a bundle (or more) to install (key '%s' in 'extra')",
                Formula::BUNDLES_TO_INSTALL_KEY));
        }

        if (!isset($this->data['extra'][Formula::INSTALLER_CLASS_KEY])) {
            throw new \UnexpectedValueException(sprintf("Package's composer.json doesn't specify an installer class (key '%s' in 'extra')",
                Formula::BUNDLES_TO_INSTALL_KEY));
        }
    }
}
