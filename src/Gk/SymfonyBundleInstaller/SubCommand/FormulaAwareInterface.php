<?php

namespace Gk\SymfonyBundleInstaller\SubCommand;

use Gk\SymfonyBundleInstaller\Formula\Formula;

interface FormulaAwareInterface
{
    /**
     * @return Formula
     */
    public function getFormula();

    /**
     * @param Formula $formula
     */
    public function setFormula(Formula $formula);
}
