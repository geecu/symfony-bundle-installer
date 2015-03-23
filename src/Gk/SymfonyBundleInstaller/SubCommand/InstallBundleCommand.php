<?php

namespace Gk\SymfonyBundleInstaller\SubCommand;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstallBundleCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('install:bundle')
            ->setDescription('Installs and configures a Symony bundle')
            ->addArgument('formula',
                InputArgument::REQUIRED,
                'Composer package name of the installer formula'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Running install:bundle');
    }
}
