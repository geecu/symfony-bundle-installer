<?php

namespace Gk\SymfonyBundleInstaller\Formula;

use Gk\SymfonyBundleInstaller\SubCommand\ComposerApplicationAwareInterface;
use Gk\SymfonyBundleInstaller\SubCommand\FormulaAwareInterface;
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

    /**
     * @var \Composer\Console\Application
     */
    protected $composerApplication;

    public function __construct(Formula $formula, InputInterface $input, OutputInterface $output, \Composer\Console\Application $composerApplication)
    {
        $this->formula = $formula;
        $this->input = $input;
        $this->output = $output;
        $this->composerApplication = $composerApplication;

        $this->application = new \Symfony\Component\Console\Application();
        $this->loadBuiltinSubCommands();
        $this->loadFormulaSubCommands();
    }

    public function execute($command)
    {
        $this->getCommand($command)
            ->run($this->input, $this->output);
    }

    protected function getCommand($command)
    {
        $command = $this->application
            ->get($command)
            ;

        if ($command instanceof FormulaAwareInterface) {
            $command->setFormula($this->formula);
        }

        if ($command instanceof ComposerApplicationAwareInterface) {
            $command->setComposerApplication($this->composerApplication);
        }

        return $command;
    }

    protected function loadBuiltinSubCommands()
    {
        $path = [
            __DIR__,
            '..',
            'SubCommand'
        ];

        $namespaceParts = explode('\\', __NAMESPACE__);
        array_pop($namespaceParts);

        $this->loadSubCommandsFromDirectory(implode(DIRECTORY_SEPARATOR, $path), implode('\\', $namespaceParts));
    }

    protected function loadFormulaSubCommands()
    {
        $installer = $this->formula->getInstaller();

        $path = [
            $installer->getDirectory(),
            'SubCommand'
        ];

        $dir = implode(DIRECTORY_SEPARATOR, $path);
        if (file_exists($dir)) {
            $this->loadSubCommandsFromDirectory($dir, $installer->getNamespace());
        }
    }

    protected function loadSubCommandsFromDirectory($dir, $namespace)
    {
        $finder = new Finder();
        $finder->files()->name('*Command.php')->in($dir);

        $prefix = $namespace.'\\SubCommand';
        foreach ($finder as $file) {
            /* @var SplFileInfo $file */
            $ns = $prefix;
            if ($relativePath = $file->getRelativePath()) {
                $ns .= '\\' . strtr($relativePath, '/', '\\');
            }
            $class = $ns . '\\' . $file->getBasename('.php');
            $r = new \ReflectionClass($class);
            if ($r->isSubclassOf('\\Gk\\SymfonyBundleInstaller\\SubCommand\\AbstractCommand')
                && !$r->isAbstract()
                && !$r->getConstructor()->getNumberOfRequiredParameters()) {
                $this->application->add($r->newInstance());
            }
        }
    }

}
