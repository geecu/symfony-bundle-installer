<?php

namespace Gk\SymfonyBundleInstaller\Formula;

use Composer\Console\Application as ComposerApplication;
use Composer\Json\JsonFile;
use Gk\SymfonyBundleInstaller\Installer\AbstractInstaller;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Formula
{
    const JSON_TYPE = 'symfony-bundle-utils-formula';
    const INSTALLER_CLASS_KEY = 'sfbu-installer';
    const BUNDLES_TO_INSTALL_KEY = 'sfbu-bundle';

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

    public function run(InputInterface $input, OutputInterface $output, ComposerApplication $composerApplication)
    {
        $output->writeln(sprintf('<info>Running %s</info>', $this->getPath()));

        $installCommands = $this->getInstaller()->getInstallCommands();
        $application = $this->getApplication($input, $output, $composerApplication);
        foreach ($installCommands as $idx=>$installCommand) {
            $output->writeln(sprintf('<info>Step %d: %s</info>', $idx + 1, $installCommand));
            $application->execute($installCommand);
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

    public function getBundlesToInstall()
    {
        $bundlesString = $this->jsonData['extra'][self::BUNDLES_TO_INSTALL_KEY];

        $bundles = array_map('trim', explode(',', $bundlesString));

        return $bundles;
    }

    protected function getApplication(InputInterface $input, OutputInterface $output, ComposerApplication $composerApplication)
    {
        if ($this->application) {
            return $this->application;
        }

        $this->application = new Application($this, $input, $output, $composerApplication);

        return $this->application;
    }

}
