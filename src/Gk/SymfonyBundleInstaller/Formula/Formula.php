<?php

namespace Gk\SymfonyBundleInstaller\Formula;

use Composer\Json\JsonFile;
use Gk\SymfonyBundleInstaller\Installer\AbstractInstaller;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Formula
{
    const JSON_TYPE = 'symfony-bundle-utils-formula';
    const INSTALLER_CLASS_KEY = 'sfbu-installer';
    const INSTALLER_BUNDLE_KEY = 'sfbu-bundle';

    protected $path;

    protected $jsonData;

    /**
     * @var AbstractInstaller
     */
    protected $installer;

    /**
     * @var Application
     */
    protected $application;

    public function __construct($path)
    {
        $this->path = $path;

        $composerJSONPath = sprintf("%s/composer.json", $this->path);
        $composerJSON = new JsonFile($composerJSONPath);
        $this->jsonData = $composerJSON->read();

        $validator = new Validator($this);
        $validator->validate();
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getAutoloaderPath()
    {
        return sprintf('%s/vendor/autoload.php', $this->getPath());
    }

    /**
     * @return mixed
     */
    public function getJsonData()
    {
        return $this->jsonData;
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<info>Running %s</info>', $this->getPath()));

        $steps = $this->getInstaller()->getSteps();
        $application = $this->getApplication($input, $output);
        foreach ($steps as $idx=>$step) {
            $output->writeln(sprintf('<info>Step %d: %s</info>', $idx + 1, $step));
            $application->execute($step);
        }
    }

    protected function registerAutoloader()
    {
        require_once($this->getAutoloaderPath());
    }

    public function getInstaller()
    {
        if ($this->installer) {
            return $this->installer;
        }

        $this->registerAutoloader();

        $installerClass = $this->jsonData['extra'][self::INSTALLER_CLASS_KEY];

        $this->installer = new $installerClass;

        return $this->installer;
    }

    protected function getApplication($input, $output)
    {
        if ($this->application) {
            return $this->application;
        }

        $this->application = new Application($this, $input, $output);

        return $this->application;
    }

}
