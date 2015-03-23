<?php

namespace Gk\SymfonyBundleInstaller\SubCommand;

use Composer\Console\Application;

trait ComposerApplicationAwareTrait
{
    /**
     * @var Composer
     */
    private $composerApplication;

    /**
     * @return Composer
     */
    public function getComposerApplication()
    {
        return $this->composerApplication;
    }

    /**
     * @param Composer $composerApplication
     * @return mixed
     */
    public function setComposerApplication(Application $composerApplication)
    {
        $this->composerApplication = $composerApplication;

        return $this;
    }
}
