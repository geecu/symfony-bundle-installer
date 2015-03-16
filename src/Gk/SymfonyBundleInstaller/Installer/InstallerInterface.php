<?php


namespace Gk\SymfonyBundleInstaller\Installer;


interface InstallerInterface
{
    /**
     * @return array of command names to be executed in order
     */
    public function getSteps();
}
