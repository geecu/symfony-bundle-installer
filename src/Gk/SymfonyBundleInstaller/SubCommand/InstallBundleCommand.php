<?php

namespace Gk\SymfonyBundleInstaller\SubCommand;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

class InstallBundleCommand extends AbstractCommand
    implements FormulaAwareInterface, ComposerApplicationAwareInterface
{
    use FormulaAwareTrait;
    use ComposerApplicationAwareTrait;

    protected function configure()
    {
        $this->setName('install:bundle')
            ->setDescription('Installs the Symfony bundle provided by the formula using composer require')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bundlesToInstall = $this->getFormula()->getBundlesToInstall();
        $composerApplication = $this->getComposerApplication();
        $composerInput = new StringInput(sprintf('require %s', join(' ', $bundlesToInstall)));
        $composerApplication->run($composerInput, $output);
    }
}
