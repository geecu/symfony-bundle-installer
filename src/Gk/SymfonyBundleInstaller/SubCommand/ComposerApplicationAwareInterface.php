<?php

namespace Gk\SymfonyBundleInstaller\SubCommand;

use Composer\Console\Application;

interface ComposerApplicationAwareInterface
{
    /**
     * @return Application
     */
    public function getComposerApplication();

    /**
     * @param Application $composer
     * @return mixed
     */
    public function setComposerApplication(Application $composerApplication);
}
