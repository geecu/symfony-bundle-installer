<?php

namespace Gk\SymfonyBundleInstaller\SubCommand;

use Composer\Composer;

interface ComposerAwareInterface
{
    /**
     * @return Composer
     */
    public function getComposer();

    /**
     * @param Composer $composer
     * @return mixed
     */
    public function setComposer(Composer $composer);
}
