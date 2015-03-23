<?php

namespace Gk\SymfonyBundleInstaller\SubCommand;

use Composer\Composer;

trait ComposerAwareTrait
{
    /**
     * @var Composer
     */
    private $composer;

    /**
     * @return Composer
     */
    public function getComposer()
    {
        return $this->composer;
    }

    /**
     * @param Composer $composer
     * @return mixed
     */
    public function setComposer(Composer $composer)
    {
        $this->composer = $composer;

        return $this;
    }
}
