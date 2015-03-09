<?php

namespace Gk\SymfonyBundleInstaller\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
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
        $output->writeln('Nothing here yet');
    }

}
