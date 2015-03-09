<?php

namespace Gk\SymfonyBundleInstaller\Command;

use Composer\Command\Command;
use Composer\Factory;
use Composer\IO\ConsoleIO;
use Composer\Package\CompletePackage;
use Gk\SymfonyBundleInstaller\Formula\Formula;
use Gk\SymfonyBundleInstaller\Formula\FormulaManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

class InstallCommand extends Command
{

    protected function configure()
    {
        $this->setName('install')
            ->setDescription('Installs and configures a Symony bundle')
            ->addArgument('formula',
                InputArgument::REQUIRED,
                'Composer package name of the installer formula'
            )
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $formulaName = $input->getArgument('formula');

        if (0 === strpos($formulaName, '/')) {
            //Passed absolute path to the formula
            $formulaPath = $formulaName;
        } else {
            $formulaPath = $this->installGlobalComposer($formulaName, $output);
        }

        $formula = $this->getFormula($formulaPath);
        $formula->run($input, $output);

    }

    protected function getFormula($formulaPath)
    {
        return new Formula($formulaPath);
    }

    protected function installGlobalComposer($packageName, OutputInterface $output)
    {
        $config = Factory::createConfig();

        $cwd = getcwd();
        chdir($config->get('home'));

        $package = $this->getGlobalComposerPackage($packageName);
        if (!$package) {
            $composerInput = new StringInput(sprintf('require %s', $packageName));
            $this->getApplication()->run($composerInput, $output);
            $package = $this->getGlobalComposerPackage($packageName);
        }


        if (!$package) {
            throw new \Exception(sprintf('Package %s not found in %s', $package, $config->get('home')));
        }

        chdir($cwd);

        return $this->getComposer()->getInstallationManager()->getInstallPath($package);
    }

    /**
     * @param $packageName
     * @return CompletePackage
     */
    protected function getGlobalComposerPackage($packageName)
    {
        $composer = $this->getComposer();
        $localRepo = $composer->getRepositoryManager()->getLocalRepository();
        $packages = $localRepo->findPackages($packageName);

        if (count($packages)) {
            return array_shift($packages);
        }
    }

}
