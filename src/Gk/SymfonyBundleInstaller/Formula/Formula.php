<?php

namespace Gk\SymfonyBundleInstaller\Formula;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Formula
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('Running %s', $this->path));
    }
}
