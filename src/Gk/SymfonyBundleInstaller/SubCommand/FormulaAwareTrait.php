<?php

namespace Gk\SymfonyBundleInstaller\SubCommand;

use Gk\SymfonyBundleInstaller\Formula\Formula;

trait FormulaAwareTrait
{
    /**
     * @var Formula
     */
    private $formula;

    /**
     * @return Formula
     */
    public function getFormula()
    {
        return $this->formula;
    }

    /**
     * @param Formula $formula
     */
    public function setFormula(Formula $formula)
    {
        $this->formula = $formula;

        return $this;
    }


}
