<?php

namespace Gk\SymfonyBundleInstaller\Formula;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class Application
{
    /**
     * @var Formula
     */
    protected $formula;

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Console\Application
     */
    protected $application;

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    public function __construct(Formula $formula, InputInterface $input, OutputInterface $output)
    {
        $this->formula = $formula;
        $this->input = $input;
        $this->output = $output;

        $this->application = new \Symfony\Component\Console\Application();
        $this->loadBuiltinSteps();
        $this->loadFormulaSteps();
    }

    public function execute($command)
    {
        $this->application->get($command)->run($this->input, $this->output);
    }

    protected function loadBuiltinSteps()
    {
        $path = [
            __DIR__,
            '..',
            'Step'
        ];

        $namespaceParts = explode('\\', __NAMESPACE__);
        array_pop($namespaceParts);

        $this->loadStepsFromDirectory(implode(DIRECTORY_SEPARATOR, $path), implode('\\', $namespaceParts));
    }

    protected function loadFormulaSteps()
    {
        $installer = $this->formula->getInstaller();

        $path = [
            $installer->getDirectory(),
            'Step'
        ];

        $dir = implode(DIRECTORY_SEPARATOR, $path);
        if (file_exists($dir)) {
            $this->loadStepsFromDirectory($dir, $installer->getNamespace());
        }
    }

    protected function loadStepsFromDirectory($dir, $namespace)
    {
        $finder = new Finder();
        $finder->files()->name('*Step.php')->in($dir);

        $prefix = $namespace.'\\Step';
        foreach ($finder as $file) {
            /* @var SplFileInfo $file */
            $ns = $prefix;
            if ($relativePath = $file->getRelativePath()) {
                $ns .= '\\' . strtr($relativePath, '/', '\\');
            }
            $class = $ns . '\\' . $file->getBasename('.php');
            $r = new \ReflectionClass($class);
            if ($r->isSubclassOf('\\Gk\\SymfonyBundleInstaller\\Step\\AbstractStep')
                && !$r->isAbstract()
                && !$r->getConstructor()->getNumberOfRequiredParameters()) {
                $this->application->add($r->newInstance());
            }
        }
    }

}
