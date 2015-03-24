<?php

namespace Gk\SymfonyBundleInstaller\SubCommand;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

class InstallKernelCommand extends AbstractCommand
    implements FormulaAwareInterface
{
    use FormulaAwareTrait;

    protected function configure()
    {
        $this->setName('install:kernel')
            ->setDescription('Adds formula\'s bundle in the application\'s kernel')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bundlesToInstall = $this->getFormula()->getBundlesToInstall();
    }
}
